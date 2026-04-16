<?php
define('BASE_URL', __DIR__);
include_once BASE_URL.'/app/helpers/getUrl.php';
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