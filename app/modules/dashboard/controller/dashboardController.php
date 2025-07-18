<?php 
require_once __DIR__ . '/../../../helpers/session.php';
class DashboardController{
    public function __construct(){

    }
    
    public function dashboard(){
        
        // $path = ;
        return include_once __DIR__ . '/../views/dashboardView.php';
    }
}


?>