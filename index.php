<?php
include_once __DIR__.'/Core/Helpers/GetUrl.php';
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