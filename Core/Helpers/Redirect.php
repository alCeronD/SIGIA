<?php
function redirect($url)
{
    echo "<script type='text/javascript'>"
        . "window.location.href='$url'"
        . "</script>";
}

Class Redirect{

    public static function reditectTo($url)
    {
        echo "<script type='text/javascript'>"
            . "window.location.href='$url'"
            . "</script>";
    }

    public static function fast(string $url) {
        header("Location: $url");
        exit;
    }
}

?>