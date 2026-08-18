<?php

use ZipStream\Test\Util;

require_once __DIR__ . '/../../../Config/Conn.php';
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_URL . '/../Modules/Login/Const/LoginConst.php';

class LoginController extends ConfigController
{
    private $conn;
    private Regex $regex;
    protected UsuariosModel $usuModel;
    protected UsuariosRolesModel $usuRolModel;
    protected RolesModel $rModel;

    public function __construct()
    {
        $this->regex = new Regex();
        $this->usuModel = new UsuariosModel();
        $this->usuRolModel = new UsuariosRolesModel();
        $this->rModel = new RolesModel();
    }

    public function createRoutes()
    {
        throw new \Exception('Not implemented');
    }

    public function index()
    {
        $path = realpath(BASE_URL . ROUTE_MAIN_ROUTE);
        parent::renderView($path);
    }

    public function login()
    {
        try {
            header(CONTENT_TYPE);
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                throw new Exception("Método no permitodo", HttpStatus::METHOD_NOT_ALLOWED);
            $data = UtilsFunctions::returnGetDecode();

            if (empty($data)) throw new Exception("Datos enviados incorrectamente", HttpStatus::BAD_REQUEST);
            $mapCampos = [
                'docum' => 'documento de identidad',
                'pass' => 'contraseña'
            ];
            // validamos los campos obligatorios y capturamos la exception en caso de que error.
            $resultValidateData = UtilsFunctions::validateCampos($data, $mapCampos);
            if (!$resultValidateData[CR_STATUS]) throw new Exception($resultValidateData[CR_MESSAGE], $resultValidateData[CR_CODE_RESPONSE]);

            if (!$this->regex->validarNumeros($data['docum'])) {
                throw new Exception(MSG_ERROR_NO_LETRAS, HttpStatus::BAD_REQUEST);
            }

            // tabla usuarios
            $usuTable = [
                'table' => "{$this->usuModel->getTable()} u",
                'primaryKey' => "u.{$this->usuModel->getKeyName()}",
                'uniqueKey' => "u.usu_docum",
                'estado' => "u.usu_id_estado"
            ];

            // tabla usuarios_roles
            $usuRTable = [
                'table' => "{$this->usuRolModel->getTable()} ur",
                'foreigKeyUsu' => 'ur.usr_usu_id',
                'foreigKeyRol' => 'ur.usr_rl_id'
            ];

            // tabla roles
            $rTable = [
                'table' => "{$this->rModel->getTable()} r",
                'primaryKey' => "r.{$this->rModel->getKeyName()}"
            ];

            $sql = [
                "u.usu_id",
                "u.usu_docum",
                "u.usu_password",
                "u.usu_nombres",
                "u.usu_apellidos",
                "u.usu_telefono",
                "u.usu_id_estado",
                "u.usu_email",
                "r.rl_id",
                "r.rl_nombre"
            ];
            $usuIdPrepare[CR_DATA] = ['usu_docum' => $data['docum']];
            # Paso 1 - traer el id del usuario para validar la existencia del usuario, su rol asociado y si esta activo.
            $usuId = $this->usuModel->select(['usu_id'])
                ->from()
                ->where(['usu_docum', '=', $data['docum']])
                ->prepareSql($usuIdPrepare)
                ->get()[0]['usu_id'] ?? "";

            if (empty($usuId)) throw new Exception(MSG_RL_NO_DOCUMENT, HttpStatus::BAD_REQUEST);

            $dataPrepare[CR_DATA] = [
                'u_usu_docum' => $data['docum'],
                'u_usu_id_estado' => 1
            ];
            # Paso 2 - Validar existencia del usuario.
            $usu = $this->usuModel->select($sql)->from($usuTable['table'])
                ->innerJoin($usuRTable['table'], $usuRTable['foreigKeyUsu'], '=', $usuTable['primaryKey'])
                ->innerJoin($rTable['table'], $rTable['primaryKey'], '=', $usuRTable['foreigKeyRol'])
                ->where([$usuTable['uniqueKey'], '=', $data['docum']])
                ->where([$usuTable['estado'], '=', 1])
                ->prepareSql($dataPrepare)
                ->get()[0];

            $rolIsActivePrepare[CR_DATA] = ['rl_id' => $usu['rl_id']];

            # Paso 3 - Validar que el rol del usuario este activo.
            $rolIsActive = $this->rModel->select(['rl_status'])
                ->from()
                ->where(['rl_id', '=', $usu['rl_id']])->prepareSql($rolIsActivePrepare)->get();

            if ($rolIsActive[0]['rl_status'] !== 1) throw new Exception(MSG_RL_NO_ACTIVE, HttpStatus::UNAUTHORIZED);

            # Paso 4 - validar la password enviada y la password registrada en la base de datos
            $passWordBD = $usu['usu_password'];

            if (!$this->validatePassword($passWordBD, $data['pass'])) {
                throw new Exception(MSG_RL_DATA_ERROR, HttpStatus::BAD_REQUEST);
            }

            # Paso 5 - Crear la sesion y renderizar el menu del usuario.
            if ($rolIsActive[0]['rl_status'] === 1 && !empty($usu)) {
                session_start();
                session_regenerate_id(true);

                $permisosModel = new PermisosModel();

                $result = $permisosModel->renderMenu((int) $usu['rl_id']);
                $_SESSION[CR_USUARIO] = [
                    'id' => $usu['usu_id'],
                    'documento' => $usu['usu_docum'],
                    'nombre' => $usu['usu_nombres'],
                    'apellido' => $usu['usu_apellidos'],
                    'rol_id' => $usu['rl_id'],
                    'rol_nombre' => $usu['rl_nombre'],
                    'email' => $usu['usu_email']
                ];

                $_SESSION[CR_RENDER_MENU] = $result[CR_DATA];
            }

            Response::responseRequest(HttpStatus::OK, true, MSG_RL_CONECTADO, ['url' => CR_ROUTE_DASHBOARD_LOGIN]);
        } catch (\Exception $th) {
            Response::responseRequest($th->getCode(), false, $th->getMessage(), []);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        if (UtilsFunctions::ajaxGeneral()) {
            header(CONTENT_TYPE);
            // debo re direccionar y ahi eliminar los datos de localStorage con clear().
            Response::responseRequest(HttpStatus::OK, true, '', ['redirect' => Router::createRoute('Login', 'Login', 'index', false, 'dashboard')]);
        }

        // se coloca tambien la opcion para re direccionar de manera directa como alternativa.
        Rect::redirectTo(Router::createRoute('Login', 'Login', 'index', false, 'dashboard'));
        exit();
    }

    /**
     * Function para validar que la password enviada por el usuario y la registrada en la base de datos sea la correcta.
     *
     * @param mixed $passWordBD
     * @param mixed $passwordUser
     * @return boolean
     */
    public function validatePassword(mixed $passWordBD, mixed  $passwordUser): bool
    {

        return password_verify($passwordUser, $passWordBD);
    }
}
