<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 14.10.18
 * Time: 1:08
 */

include __DIR__ . '/navbarAdm.php';
?>
  <?=$T->breadcrumb?>
  <div class="container" id="AdminEdit">
    <H1><?=$T->head?></H1>
    <div class="row">
    <div class="col-12 offset-10">
      <?=$T->button1?>
      <?=$T->button2?>
    </div>
    </div>
    <div class="row">
      <div class="col-6">
        <?=$T->form?>
      </div>
      <!--<div class="col-6">
        <img class="avatar" src="/img/icon_login.jpg">
      </div>-->
  </div>
<?php include_once TEMPLATES . 'footer.php'; ?>