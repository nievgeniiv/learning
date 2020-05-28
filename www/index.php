<?php

include_once __DIR__. '/../back/configs/main.php';

$a = new Application();
$a->run();/*
$data = rand(1000, 9999);
$im = imagecreatetruecolor(100, 48);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledellipse($im, 0, 0, 0, 0, $black);
$font = __DIR__ . '/../back/services/fonts/Jellee-Roman.ttf';
imagettftext($im, 20, 15, 15, 43, $white, $font, $data);
header("Content-type: image/png");
imagepng($im, __DIR__ . 'imagecapcha.png');
imagedestroy($im);*/


