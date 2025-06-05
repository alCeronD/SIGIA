<?php 

class DashboardController{
    public function __construct(){

    }
    
    public function dashboard(){
        
        $path = __DIR__ . '/../views/dashboardView.php';
        // $_SESSION['css'] = 'dashboard/dashboard.css';
        return include $path;
    }
}


?>