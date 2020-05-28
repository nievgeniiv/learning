<?php
$T = Template::getInstance();
require_once __DIR__ . '/header.php';
?>

  <form method="post" action= "<?=$T->payNowButtonUrl?>">
    <input type="hidden" name="cmd" value="_xclick"> <!--_s-xclick - использовать шифрование (_xclick - без шифрования)-->
    <input type="hidden" name="business" value="<?=$T->receiverEmail?>"> <!--на какой счет переводить деньги-->
    <input type="hidden" name="item_name" value="<?=$T->itemName?>"> <!--наименование товара-->
    <input type="hidden" name="currency_code" value="<?=$T->currency?>"> <!--валюта-->
    <input type="hidden" name="lc" value="RU">  <!--страна-->
    <input type="hidden" name="amount" value="<?=$T->amount?>"> <!--цена-->
    <input type="hidden" name="rm" value="2"> <!--Этот параметр определяет, как будет передаваться информация об успешно
                                                  завершенной транзакции скрипту, указанному в параметре return.
                                                   "1" - никакие параметры передаваться не будут.
                                                   "2" - будет использоваться метод POST.
                                                   "0" - будет использоваться метод GET. По умолчанию "0". -->
    <input type="hidden" name="no_shipping" value="1"> <!--Не запрашивать адрес для доставки.
                                                           "1" - не запрашивать адрес, "0" - запрашивать-->
    <input type="hidden" name="return" value="<?=$T->returnUrl?>"> <!--URL, куда покупатель будет перенаправлен после
                                                                       успешной оплаты. Если этот параметр не передать,
                                                                       покупатель останется на сайте PayPal-->
    <input type="submit" value="Оплатить">
  </form>

<?php
require_once __DIR__ . '/footer.php'; ?>