<?php
/**
 * Created by PhpStorm.
 * User: zheny
 * Date: 24.02.2019
 * Time: 17:21
 */

class Validator
{

  public static function Email(string $data) : bool {
    $data_modern = stripslashes($data);
    $data_modern = strip_tags($data_modern);
    $data_modern = htmlspecialchars($data_modern);
    return filter_var($data_modern, FILTER_VALIDATE_EMAIL);
  }

  public static function Fio(string $data) : bool {
    $data_modern = stripslashes($data);
    $data_modern = strip_tags($data_modern);
    $data_modern = htmlspecialchars($data_modern);
    return preg_match('/^[а-яёА-ЯЁ\s]+$/u', $data_modern);
  }

  public static function Captcha(string $data) : bool
  {
    return ((int)$data === $_SESSION['captcha']);
  }

  public static function DeleteHTMLSymbol(string $data) : string
  {
    $data = stripslashes($data); //убирает экранирование символов
    $data = strip_tags($data); //убирает HTML и PHP тэги
    return htmlspecialchars($data);
  }

  public static function CheckReCaptcha(string $data) : bool
  {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = google_secret_key;
    $recaptcha_response = $data;
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);
    if ($recaptcha->score >= 0.5) {
      return true;
    } else {
      return false;
    }
  }
}