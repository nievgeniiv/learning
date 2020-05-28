<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 13.10.18
 * Time: 23:09
 */

include __DIR__ . '/../header.php';

?>
  <?=$T->navbar?>
  <?=$T->RichEdit?>
  <?=$T->breadcrumb?>
  <div class="container">
    <div class="row padding">
      <H1><?=$T->head?></H1>
    </div>
    <?=$T->form?>
  </div>

<?php include_once __DIR__ . '/../footer.php';?>