<?php
  include_once TEMPLATES . 'header.php';
?>
  <?=$T->navbar?>
  <?=$T->RichEdit?>
  <?=$T->breadcrumb?>
  <div class="container">
    <div class="row padding">
      <H1><?=$T->head?></H1>
    </div>
    <div class="row">
      <label><b>Тема практики:</b></label>
    </div>
    <div class="row">
      <label><?= $T->practic[0]['theme'] ?></label>
    </div>
    <div class="row">
      <label><b>Пример:</b></label>
    </div>
    <div class="row">
      <label><?= $T->practic[0]['example'] ?></label>
    </div>
    <div class="row">
      <label><b>Задание:</b></label>
    </div>
    <div class="row">
      <label><?= $T->practic[0]['task'] ?></label>
    </div>
    <div class="row">
      <label><b>Ответ:</b></label>
    </div>
    <div class="row">
      <label><?= $T->practic[0]['answer'] ?></label>
    </div>
    <div class="row">
        <?=$T->form?>
    </div>
    <div class="row">
      <?=$T->link?>
    </div>
  </div>
<?php include_once __DIR__ . '/../footer.php'; ?>