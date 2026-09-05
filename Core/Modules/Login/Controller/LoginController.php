<?php


require_once __DIR__ . '/../../../Config/Conn.php';
require_once __DIR__ . '/../../../Helpers/Const.php';
require_once BASE_PATH . '/../Modules/Login/Const/LoginConst.php';

class LoginController extends ConfigController
{
    private Regex $regex;
    protected UsuariosModel $usuModel;
    protected UsuariosRolesModel $usuRolModel;
    protected RolesModel $rModel;
    protected ModulosModel $mModel;
    protected RolesFuncionesModel $rfModel;
    protected TipoFuncionModel $tpModel;
    protected FuncionesModel $fModel;

    public function __construct()
    {
        $this->regex = new Regex();
        $this->usuModel = new UsuariosModel();
        $this->usuRolModel = new UsuariosRolesModel();
        $this->rModel = new RolesModel();
        $this->mModel = new ModulosModel();
        $this->rfModel = new RolesFuncionesModel();
        $this->tpModel = new TipoFuncionModel();
        $this->fModel = new FuncionesModel();
    }

    public function createRoutes()
    {
        throw new \Exception('Not implemented');
    }

    public function index()
    {
        $path = realpath(BASE_PATH . ROUTE_MAIN_ROUTE);
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
            $rolIsActive = $this->rModel->select(['rl_status', 'rl_id'])
                ->from()
                ->where(['rl_id', '=', $usu['rl_id']])
                ->prepareSql($rolIsActivePrepare)
                ->get();

            if ($rolIsActive[0]['rl_status'] !== 1) throw new Exception(MSG_RL_NO_ACTIVE, HttpStatus::UNAUTHORIZED);

            # Paso 4 - validar la password enviada y la password registrada en la base de datos
            $passWordBD = $usu['usu_password'];

            if (!$this->validatePassword($passWordBD, $data['pass'])) {
                throw new Exception(MSG_RL_DATA_ERROR, HttpStatus::BAD_REQUEST);
            }

            if ($rolIsActive[0]['rl_status'] === 1 && !empty($usu)) {
                session_start();
                session_regenerate_id(true);

                $dataMenu = $this->renderMenu($rolIsActive);
                $_SESSION[CR_USUARIO] = [
                    'id' => $usu['usu_id'],
                    'documento' => $usu['usu_docum'],
                    'nombre' => $usu['usu_nombres'],
                    'apellido' => $usu['usu_apellidos'],
                    'rol_id' => $usu['rl_id'],
                    'rol_nombre' => $usu['rl_nombre'],
                    'email' => $usu['usu_email']
                ];
                # Paso 5 - crear la variable de session para renderizar las vistas.
                $_SESSION[CR_RENDER_MENU] = $dataMenu;
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


    /**
     * Funcionalidad para crear el menu del usuario basado en sus permisos
     *
     * @param array $rolIsActive - array con todos los datos del rol del usuario.
     * @return array
     */
    protected function renderMenu(array $rolIsActive = [])
    {

        if ($rolIsActive[0]['rl_status'] === 1 && !empty($usu)) {
            session_start();
            session_regenerate_id(true);
        }
        $rolId = $rolIsActive[0]['rl_id'];
        # Paso 5.1 - Traer los modulos asociados basados en el rol del usuario.
        $sqlMmodel = [
            "DISTINCT mo.id_m AS 'idModulo'",
            "mo.nombre_modulo AS 'nombreModulo'",
            "mo.icono AS 'iconModulo'"
        ];

        $sqlMModelPrepare[CR_DATA] = [
            'r_rl_id' => $rolId
        ];

        $modulesFromUser = $this->mModel->select($sqlMmodel)
            ->from("{$this->mModel->getTable()} mo")
            ->innerJoin("{$this->fModel->getTable()} fu", "fu.id_modulo", "=", "mo.id_m")
            ->innerJoin("{$this->rfModel->getTable()} rof", "rof.rlp_id_funcion", "=", "fu.id_funcion")
            ->innerJoin("{$this->rModel->getTable()} r", "r.rl_id", "=", "rof.rlp_id_rl")
            ->where(["r.rl_id", '=', $rolId])->prepareSql($sqlMModelPrepare)->get();

        // ids de los modulos que el usuario esta asociado
        $idsModulosFromUser = array_column($modulesFromUser, 'idModulo');

        # Paso 5.2 - traer los modulos que esten asociados al usuario
        $sqlFModel = [
            " DISTINCT fu.nombre_funcion_user AS 'nombreFuncionUser'",
            "fu.id_funcion AS 'idFunción'",
            "fu.nombre_funcion AS 'nombreFuncionController'",
            "fu.is_main_view AS 'isMainView'",
            "fu.nameController AS 'controlador'",
            "mo.nombre_modulo AS 'nombreModulo'",
            "mo.icono AS 'icono'"
        ];

        $rolId = $rolIsActive[0]['rl_id'];

        $referencesIn = [];

        $modAssocPrepare[CR_DATA] = [
            'tpf_id_tp_funcion' => 1,
            'r_rl_id' => $rolId,
        ];
        foreach ($idsModulosFromUser as $key => $idM) {
            $reference = "mo_id_m{$key}";
            $modAssocPrepare[CR_DATA][$reference] = $idM;
            // $referencesIn[] = $reference;
            $referencesIn[$reference] = $idM;
        }

        # Consulta del paso 5.2
        $modAssoc = $this->fModel->select($sqlFModel)
            ->from("{$this->fModel->getTable()} fu")
            ->innerJoin("{$this->tpModel->getTable()} tpf", "tpf.{$this->tpModel->getKeyName()}", '=', "fu.tp_funcion")
            ->innerJoin("{$this->rfModel->getTable()} rof", "rof.rlp_id_funcion", "=", "fu.{$this->fModel->getKeyName()}")
            ->innerJoin("{$this->rModel->getTable()} r", "r.{$this->rModel->getKeyName()}", "=", "rof.rlp_id_rl")
            ->innerJoin("{$this->mModel->getTable()} mo", "mo.{$this->mModel->getKeyName()}", "=", "fu.id_modulo")
            ->where(['tpf.id_tp_funcion', '=', 1])
            ->where(['r.rl_id', '=', $rolIsActive[0]['rl_id']])
            ->whereIn($referencesIn, 'mo.id_m')
            ->prepareSql($modAssocPrepare)
            ->get(); //en el parametro IN LE ENVIAMOS EL ARREGLO CON LAS REFERENCIAS, es decir, mo_id_m1, mo_id_m2, mo_id_mN;


        // AHORA QUE YA TENEMOS TODO, NECESITAMOS CREAR LAS VISTAS.
        # 5.3 - Crear las vistas
        $nameModules = array_column($modulesFromUser, 'nombreModulo');

        $menu = [];
        foreach ($modAssoc as $key => $value) {

            $nombreModulo = $value['nombreModulo'] ?? null;
            $controlador = $value['controlador'] ?? null;
            $funcion = $value['nombreFuncionController'] ?? null;
            $icono = $value['icono'] ?? null;
            $nombreFuncionUser = $value['nombreFuncionUser'] ?? null;

            $isMainView = $value['isMainView'] ?? null;

            if ($isMainView === 1) {
                $menu[$nombreModulo]['mainView'] = [
                    'labelFuncion' => $nombreFuncionUser,
                    'titleModule' => $nombreModulo,
                    'url' => Router::createRoute($nombreModulo, $controlador, $funcion, false, CR_DASHBOARD_LOWER_CASE),
                    'icono' => $icono
                ];
            }

            if ($isMainView === 0) {
                $menu[$nombreModulo]['secondView'][] = [
                    'titleModule' => $nombreModulo,
                    'labelFuncion' => $nombreFuncionUser,
                    'url' => Router::createRoute($nombreModulo, $controlador, $funcion, false, CR_DASHBOARD_LOWER_CASE),
                    'icono' => $icono
                ];
            }
        }

        $menuMainView = [];
        $menuSecondView = [];
        // extraemos las vistas del menu dependiendo de si son primarias o secundarias.
        foreach ($menu as $key => $views) {

            if (array_key_exists('mainView', $views)) {
                $menuMainView[] = $views['mainView'];
            }


            if (array_key_exists('secondView', $views)) {
                array_push($menuSecondView, ...$views['secondView']);
            }
        }

        return [
            'menuMainView' => $menuMainView,
            'menuSecondView' => $menuSecondView
        ];
    }
}
