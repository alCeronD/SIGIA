<?php
// function redirect($url)
// {
//     echo "<script type='text/javascript'>"
//         . "window.location.href='$url'"
//         . "</script>";
// }

class Redirect{

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