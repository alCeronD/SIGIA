<?php 

class DashboardController{
    public function __construct(){

    }
    
    public function dashboard(){
        
        $path = __DIR__ . '/../views/dashboardView.php';
        return include $path;
    }
}


?>