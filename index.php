<?php 

/**
 * mostramos enrutador y este debe de redireccionar a login.
 * 
 * 
 */


//TODO: 
/**
 * Estudiar como funciona los enrutadores o el frontController para solo usar index.php como enrutador de todas las vistas.
 * 
 */

 require_once 'app/helpers/renderView.php';
 require_once 'app/modules/login/controller/loginController.php';
 $render = new RenderView();

 $loginController = new LoginController();

 echo $loginController->getName();

 $render->renderView('login','loginView.php');

?>
