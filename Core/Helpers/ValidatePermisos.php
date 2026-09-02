<?php
require_once __DIR__ . '/Autoload.php';
require_once __DIR__ . '/Const.php';

/**
 * Archivo para validar cada función ejecutada en los archivos.
 * Necesito traer la sessión, el getUrl y el response del para enviar la respuesta al front.
 * en esta clase requiero los modelos de usuarios, modelos de roles
 * y los modelos de roles_funciones para validar que exista el usuario
 *
 * Esta clase nos sirve para validar las acciones de los usuarios que este puede acceder.
 * Pasos para ejecutar el proceso y pre requisitos
 *
 * - Saber cual es el id del modulo
 * - el id de la funcion
 * - verificar que el modulo este activo
 * - verificar que la funcion este asociada al modulo
 * - verificar que la funcion este asociada al ROL.
 */
class ValidatePermisos
{
    protected FuncionesModel $fModelo;
    protected ModulosModel $mModel;
    protected RolesFuncionesModel $rfModel;
    protected RolesModel $rModel;
    private array $publicFunctions = [
        'Login' => ['login', 'logout', 'index'],
        'Dashboard' => ['dashboard'],
        'Areas' => ['getData'],
        'Tipodocumento' => ['getData'],
        'Permisos' => ['permisosIndexView'],
        'Roles' => ['mostrarRoles', 'getData', 'getPermisosRolAsig'],
        'Funciones' => ['getData']
    ];
    public function __construct()
    {
        $this->fModelo = new FuncionesModel();
        $this->mModel = new ModulosModel();
        $this->rfModel = new RolesFuncionesModel();
        $this->rModel = new RolesModel();
    }

    public function validateAccess(String $modulo = '', String $funcion = '')
    {
        // validamos si la funcionalidad no debe de ser validada
        if ($this->isValidate($funcion, $modulo)) {
            return ['status' => true];
        }

        // devolvemos el resultado de la validacion de la funcionalidad
        return $this->checkDbPermission($funcion, $modulo);
    }

    /**
     * Funcionalidad para validar si la funcion a enviar debe de ser validada o no en la base de datos
     *
     * @param string $function
     * @return boolean
     */
    public function isValidate(String $function = '', String $modulo = '')
    {
        return isset($this->publicFunctions[$modulo]) && in_array($function, $this->publicFunctions[$modulo], true);
    }

    public function checkDbPermission(String $funcion = '', String $modulo = '')
    {
        try {
            // rolId de la sesion actual del usuario:
            $dataRol = Session::getRol();
            $rolId = $dataRol['rol_id'];

            $nombreRol = $dataRol['rol_nombre'];


            # paso 1- validar la sesion, en caso de que no este la sesion iniciada, devolver al index.
            if (session_status() === PHP_SESSION_NONE || !isset($_SESSION[CR_USUARIO])) {
                if ($modulo === 'Login') return; //valido si el modulo es login, asi no validamos los permisos
                // si es peticion fetch
                if (UtilsFunctions::ajaxGeneral()) {
                    header(CONTENT_TYPE);
                    Response::responseRequest(HttpStatus::UNAUTHORIZED, false, 'No tienes permisos para acceder a este sitio, seras re direccionado al portal inicial', []);
                } else {
                    // si es un acceso mediante php puro.
                    Rect::redirectTo(Router::createRoute(CR_LOGIN, CR_LOGIN, 'index', 'false', 'index'));
                }
            }

            # Paso 1.1 - Valido que el rol sea super administrador, en caso de que lo SEA, ESTE se valida el bypass.
            if ($nombreRol === CR_ROL_SUPER_ADMIN) {
                return [
                    CR_STATUS => true,
                    CR_MESSAGE => "",
                    CR_CODE_RESPONSE => 0
                ];
            }

            # paso 2 - traer id del modulo
            $prepareData[CR_DATA] = ['nombre_modulo' => $modulo];

            $dataModulo = $this->mModel->select()
                ->from()
                ->where(['nombre_modulo', '=', $modulo])
                ->prepareSql($prepareData)
                ->get()[0];

            $idModulo = $dataModulo['id_m'];

            $dataPrepare[CR_DATA] = ['nombre_funcion' => $funcion];

            if (empty($idModulo)) throw new Exception("El identificador del módulo no existe", HttpStatus::NOT_FOUND);

            # paso 3 - validar si el modulo esta disponible para su acceso, es decir, su estado.
            $estadoModulo = $dataModulo['status_modulo'];


            if ($estadoModulo === 0) throw new Exception("El módulo se encuentra inhabilitado temporalmente", HttpStatus::FORBIDDEN);


            # paso 3 - saber el id del modulo asociado al id del modulo en la tabla funciones
            $dataPrepareidFuncion[CR_DATA] = ['nombre_modulo' => $modulo, 'nombre_funcion' => $funcion];
            $idFuncion = $this->fModelo->select(['id_funcion'])
                ->from()
                ->innerJoin($this->mModel->getTable(), 'id_m', '=', 'id_modulo')
                ->where(['nombre_modulo', '=', $modulo])
                ->where(['nombre_funcion', '=', $funcion])
                ->prepareSql($dataPrepareidFuncion)
                ->get();
            if (empty($idFuncion)) throw new Exception("La funcionalidad no existe en la base de datos", HttpStatus::NOT_FOUND);


            # paso 3 - validar que el rol pueda acceder a esa funcion.
            $sql = [
                "f.id_funcion as 'idFuncion'"
            ];
            // tabla roles_funciones
            $rolesFuncionesTable = [
                'table' => "{$this->rfModel->getTable()} rf",
                'foreingKeyFuncion' => "rf.rlp_id_funcion",
                'foreignKeyRoles' => "rf.rlp_id_rl"
            ];
            $rolesTable = [
                'table' => "{$this->rModel->getTable()} ro",
                'primaryKey' => "ro.{$this->rModel->getKeyName()}"
            ];
            // tabla funciones
            $funcionesTable = [
                'table' => "{$this->fModelo->getTable()} f",
                'primaryKey' => "f.{$this->fModelo->getKeyName()}",
                'foreignKeyModulo' => "f.id_modulo"
            ];
            $modulosTable = [
                'table' => "{$this->mModel->getTable()} mo",
                'primaryKey' => "mo.{$this->mModel->getKeyName()}"
            ];
            $data = [
                'ro_rl_id' => $rolId,
                'rf_rlp_id_funcion' => $idFuncion[0]['id_funcion']
            ];
            $dataPrepareIsValid[CR_DATA] = $data;

            # paso 3 - validar que el Id de la funcion este asociado al id del modulo en la tabla funciones.
            $isValidate = $this->fModelo->select($sql)
                ->from($funcionesTable['table'])
                ->innerJoin($rolesFuncionesTable['table'], $rolesFuncionesTable['foreingKeyFuncion'], '=', $funcionesTable['primaryKey'])
                ->innerJoin($rolesTable['table'], $rolesTable['primaryKey'], '=', $rolesFuncionesTable['foreignKeyRoles'])
                ->innerJoin($modulosTable['table'], $modulosTable['primaryKey'], '=', $funcionesTable['foreignKeyModulo'])
                ->where([$rolesTable['primaryKey'], '=', $rolId])
                ->where([$rolesFuncionesTable['foreingKeyFuncion'], '=', $idFuncion[0]['id_funcion']])->prepareSql($dataPrepareIsValid)->get();


            if (count($isValidate) === 0) {
                throw new Exception("No tienes permisos para acceder a esta funcionalidad. Por seguridad, seras re direccionado al inicio de sesión", HttpStatus::UNAUTHORIZED);
            }
            return [
                CR_STATUS => true,
                CR_MESSAGE => "",
                CR_CODE_RESPONSE => 0
            ];
        } catch (\Exception $th) {
            if (UtilsFunctions::ajaxGeneral()) {
                header(CONTENT_TYPE);
                return [
                    CR_STATUS => false,
                    CR_MESSAGE => $th->getMessage(),
                    CR_CODE_RESPONSE => $th->getCode()
                ];
            }
            return [
                CR_STATUS => false,
                CR_MESSAGE => $th->getMessage(),
                CR_CODE_RESPONSE => $th->getCode()
            ];
        }
    }
}


// function validatePermisos(String $modulo, String $funcion)
// {

//     $dataResponse = [
//         'status' => false,
//         'data' => [],
//         'message' => "No tienes permisos para realizar esta acción."
//     ];
//     if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
//         if ($modulo === 'login') return;

//         if (UtilsFunctions::ajaxGeneral()) {
//             header("Content-Type: application/json");

//             Response::fail("Sesión no iniciada o expirada.", $dataResponse);
//         } else {
//             echo json_encode(['error'], JSON_PRETTY_PRINT);
//             header("Location: " . Router::createRoute('Login', 'Login', 'index', false, 'index'));
//         }
//         exit();
//     }

//     require_once __DIR__ . "/../Modules/Permisos/Controller/PermisosController.php";
//     $objPermisos = new PermisosController();

//     $idNombreModulo = $objPermisos->gidIdModulo($modulo);
//     $idFuncion = $objPermisos->getIdFuncion($funcion, $modulo, $idNombreModulo);

//     if (session_status() === PHP_SESSION_NONE || !isset($_SESSION['usuario'])) {
//         if ($modulo === 'login') return;

//         if (UtilsFunctions::ajaxGeneral()) {
//             Response::fail("Sesión no iniciada o expirada.");
//         } else {
//             Redirect::fast(Router::createRoute('Login', 'Login', 'index', false, 'index'));
//         }
//         exit();
//     }

//     $rolId = $_SESSION['usuario']['rol_id'];
//     $isValidate = $objPermisos->validateRolFuncion($rolId, $idFuncion);

//     if (!$isValidate) {
//         if (UtilsFunctions::ajaxGeneral()) {

//             // TODO: re hacer el response de la función fail y success.
//             http_response_code(403);
//             echo json_encode($dataResponse, JSON_PRETTY_PRINT);
//             exit();

//             // fail("No tienes permisos para esta acción.",$dataResponse);
//         } else {
//             Rect::fast(Router::createRoute('Login', 'Login', 'index', false, 'index'));
//         }
//         exit();
//     }
// }