<?php 
include_once '../proyecto_sigia/app/helpers/getUrl.php';


if (ajaxGeneral()) {
    resolve();
    exit;
}else {
    echo "<div class='container'>";
    if (isset($_GET['modulo'])){
        resolve();
    }else{
        redirect(getUrl('login','login','index',false,false));
    }
}



