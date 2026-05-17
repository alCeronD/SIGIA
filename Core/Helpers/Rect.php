<?php
Class Rect{

    public static function redirectTo($url)
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
