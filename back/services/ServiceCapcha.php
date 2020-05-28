<?php

Class ServiceCapcha
{
  public static function CreateCaptcha()
  {
    try {
      $data = random_int(1000, 9999);
    } catch
    (Exception $e) {
    }
    $_SESSION['captcha'] = $data;
    $im = imagecreatetruecolor(100, 48);
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledellipse($im, 0, 0, 500, 500, $white);
    $font = __DIR__ . '/../../www/fonts/Jellee-Roman.ttf';
    imagettftext($im, 20, 15, 15, 43, $black, $font, $_SESSION['captcha']);
    header('Content-type: image/png');
    imagepng($im);
  }
}