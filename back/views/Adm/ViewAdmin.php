<?php
  include_once TEMPLATES . 'header.php';
?>
  <?=$T->navbar?>
  <?=$T->breadcrumb?>
  <div class="container" id="adminTableAndPagination">
    <H1><?=$T->head?></H1>
    <div class="row">
      <div class="col">
        <admin :link="'/admin/getData/'"></admin>
      </div>
    </div>
  </div>
<?php include_once TEMPLATES . 'footer.php'; ?>

