<?php
session_start();

function Captcha($length = 4) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$captcha = Captcha();
$_SESSION['captcha'] = strtolower($captcha);

header("Content-type: image/png");
$im = imagecreatetruecolor(100, 30);
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);

imagefill($im, 0, 0, $bg);

imagestring($im, 5, 5, 5, $captcha, $fg);

imagepng($im);

imagedestroy($im);