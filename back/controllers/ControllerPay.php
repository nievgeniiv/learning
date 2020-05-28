<?php


class ControllerPay extends Controller
{
  private $currency = 'RUB';
  private $price = '20.00';

  public function run()
  {
    switch ($this->url[1]){
      case '':
        $this->actView();
        return;
      case 'validate':
        $this->actPayValidate();
        return;
    }
    $this->act404();
  }

  public function actView()
  {
    $t = Template::getInstance();
    $t->payNowButtonUrl = 'https://www.sandbox.paypal.com/cgi-bin/websc'; /* sandbox - тестовый paypal для отработки
                                                                             методики. Для реального счета использовать
                                                                             https://www.paypal.com/cgi-bin/websc*/
    $t->receiverEmail = 'zhenya.ni.92-facilitator@mail.ru';               // счет продавца
    $t->itemName = 'Урок 1';                                              // наименование товара
    $t->currency = $this->currency;                                       // валюта
    $t->amount = $this->price;                                            // цена товара
    $t->returnUrl = 'http://192.168.0.102/pay/validate';                  /* URL, куда покупатель будет перенаправлен
                                                                             после успешной оплаты. Если этот параметр не
                                                                             передать, покупатель останется на сайте PayPal*/
    require_once TEMPLATES . 'ViewPay.php';
  }

  public function actPayValidate()
  {
    $service = new ServicePaypalIpn('form1', $this->price, $this->currency);
    $service->readData();
    $service->checkTransaction();
    $t = Template::getInstance();
    if ($service->isErrors() === true){
      $t->page_title = 'Ошибка исполениея платежа';
      $t->errors = $service->getErrors();
      require_once TEMPLATES . 'ViewPayValidate.php';
      return;
    }
    $t->page_title = 'Платеж прошел успешно';
    require_once TEMPLATES . 'ViewPayValidate.php';
  }
}