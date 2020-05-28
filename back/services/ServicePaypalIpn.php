<?php
class ServicePaypalIpn
{
  private $data;
  private $errors;
  private $key;
  private $price;
  private $currency;

  public function __construct(string $formkey, string $formprice, string $formcurrency)
  {
    $this->key = $formkey;
    $this->price = $formprice;
    $this->currency = $formcurrency;
  }

  public function readData()
  {
    if (isset($_SESSION['paypal'][$this->key])){
      $row = $_SESSION['paypal'][$this->key];
      $this->errors = $row['errors'];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      $this->data = $_POST;
      $this->errors = '';
    }
  }

  public function checkTransaction()
  {
    switch ($this->data['payment_status']){
      case 'Pending':
        $this->errors['payment_status'] = 'Платеж задержан.';
        break;
      case 'Failed':
        $this->errors['payment_status'] = 'Платеж не прошел.';
        break;
      case 'Denied':
        $this->errors['payment_status'] = 'Платеж отменен продавцом.';
        break;
    }
   if ($this->data['mc_gross'] !== $this->price){
     $this->errors['mc_gross'] = 'Сумма оплаты не совпадает с ценой товара.';
   }
   if ($this->data['mc_currency'] !== $this->currency){
     $this->errors['mc_currency'] = 'Валюта платежа не совпадает с валютой товара.';
   }
  }

  public function getErrors() : array
  {
    return $this->errors;
  }

  public function isErrors() : bool
  {
    return !empty($this->errors);
  }

  public function clear()
  {
    unset($_SESSION['forms'][$this->key]);
  }
}