<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 08.01.19
 * Time: 20:38
 */

class ServiceForm
{
  private $data;
  private $errors;
  private $key;

  /**
   * ServiceForm constructor.
   * @param $formKey
   */

  public function __construct(string $formKey)
  {
    $this->key = $formKey;
  }

  public function readData(bool $vue = false)
  {
    if (isset($_SESSION['forms'][$this->key])) {
      $raw = $_SESSION['forms'][$this->key];
      $this->data = $raw['data'];
      $this->errors = $raw['errors'];
    }
    if ($vue === true) {
      unset($this->errors);
      $raw = file_get_contents('php://input');
      $this->data = json_decode($raw, true);
      $this->errors = [];
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      unset($this->errors);
      $this->data = $_POST;
      $this->errors = [];
    }
  }

  /**
   * @param string $name
   * @param string $type
   * @param bool $required
   */
  public function checkField(string $name, string $type, bool $required)
  {
    $data = trim($this->data[$name]);
    if ($required === true && empty($data) === true){
      $this->errors[$name] = 'Данное поле незаполнено';
      return;
    }
    if ($required === false && empty($data) === false){
      return;
    }
    switch ($type) {
      case 'e-mail':
        $ok = Validator::Email($data);
        if ($ok === false) {
          $this->errors[$name] = 'Неверный тип данных';
        }
        break;
      case 'FIO':
        $ok = Validator::Fio($data);
        if ($ok === false) {
          $this->errors[$name] = 'Неверный тип данных';
        }
        break;
      case 'captcha':
        $ok = Validator::Captcha($data);
        if ($ok === false) {
          $this->errors[$name] = 'Неверно введена каптча';
        }
        break;
      case 'recaptcha':
        $ok = Validator::CheckReCaptcha($data);
        if ($ok === false) {
          $this->errors[$name] = 'Вы являетесь ботом!';
        }
        break;
    }
    $this->data[$name] = Validator::DeleteHTMLSymbol($data);
  }

  public function saveData()
  {
    $_SESSION['forms'][$this->key] = [
      'data' => $this->data,
      'errors'=>$this->errors];
  }

  public function isError() : bool
  {
    return !empty($this->errors);
  }

  public function getData() : array
  {
    return $this->data;
  }

  public function getErrors() : array
  {
    return $this->errors;
  }

  public function setValue(string $name, $value)
  {
    $this->errors[$name] = $value;
  }

  public function getValue(string $name)
  {
    return $this->data[$name];
  }

  public function __isset(string $name) : bool
  {
    return isset($this->data[$name]);
  }

  public function clear()
  {
    unset($_SESSION['forms'][$this->key]);
  }

}