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
      <label><b>Тема вопроса:</b></label>
    </div>
    <div class="row">
      <label><?= $T->help['message'] ?></label>
    </div>
    
    <div class="row">
      <?=$T->form?>
    </div>
    <div class="row">
      <?=$T->link?>
    </div>
  </div>
<?php include_once __DIR__ . '/../footer.php'; ?>