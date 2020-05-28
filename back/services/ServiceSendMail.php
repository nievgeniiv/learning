<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 25.03.19
 * Time: 20:55
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ServiceSendMail
{
  public static function SendMail(string $theme, string $text_message) : bool
  {
    // Load Composer's autoloader
    require 'PHPMailer/vendor/autoload.php';

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->isSMTP();
      $mail->Host = hostSMTP;
      $mail->SMTPAuth = true;                          // включить аутентификацию
      $mail->Username = fromLogin;                     // Логин без @домен
      $mail->Password = fromPasswd;               // Пароль
      $mail->SMTPSecure = 'tls';                       // Включить ssl шифрование
      $mail->Port = 587;                               // Порт TCP
      $mail->CharSet = 'UTF-8';                        // Кодировка

      //Recipients
      $mail->setFrom(fromEmail, fromName);      //Адрес отправителя
      $mail->addAddress('zhenya.ni.92@mail.ru', 'Женя');     // Адрес получателя
      
      // Content
      $mail->isHTML(true);                       // Email в формате HTML
      $mail->Subject = $theme;                          // Тема письма
      $mail->Body = $text_message;                      // Текст письма

      $mail->send();
      return true;
    } catch (Exception $e) {
      // Обработка ошибки оптравки сообщения
      return false;
    }
  }
}




