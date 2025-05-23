<?php 
dd("haaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
include_once '../proyecto_sigia/app/helpers/getUrl.php';
echo "<div class='container'>";

if (isset($_GET['modulo'])){
    resolve();
}else{
    // dd(getUrl('login','loginController','index',false,'login'));
    redirect(getUrl('login','login','index',false,false));
}



