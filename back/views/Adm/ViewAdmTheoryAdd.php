<?php
include_once TEMPLATES . 'header.php';
?>
<?=$T->RichEdit?>
<?=$T->navbar?>
<?=$T->breadcrumb?>
  <div class="container">
    <div class="row padding">
      <H1><?=$T->head?></H1>
    </div>
    <div class="row padding">
      <?=$T->form?>
    </div>
  </div>

<?php include_once __DIR__ . '/../footer.php'; ?>