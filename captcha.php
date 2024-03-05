<?php
session_start();
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$captchaLength = 4;
$captcha = '';
for ($i = 0; $i < $captchaLength; $i++) {
    $captcha .= $characters[random_int(0, strlen($characters) - 1)];
}
$_SESSION['captcha'] = strtolower($captcha);
header("Content-type: image/png");
$im = imagecreatetruecolor(100, 30);
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 5, 5, $captcha, $fg);
imagepng($im);
imagedestroy($im);