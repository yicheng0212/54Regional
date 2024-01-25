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

// 生成隨機驗證碼
$captcha = Captcha();
$_SESSION['captcha'] = strtolower($captcha);

header("Content-type: image/png");
$im = imagecreatetruecolor(100, 30);
$bg = imagecolorallocate($im, 22, 86, 165); // 設定背景顏色
$fg = imagecolorallocate($im, 255, 255, 255); // 設定文字顏色

// 填充背景
imagefill($im, 0, 0, $bg);

// 添加文字到圖片
imagestring($im, 5, 5, 5, $captcha, $fg);

// 輸出圖片並釋放記憶體
imagepng($im);
imagedestroy($im);