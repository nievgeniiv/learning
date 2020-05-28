<?php
include_once TEMPLATES . 'header.php';
?>
  <?=$T->navbar?>
  <?=$T->breadcrumb?>
  <div class="container" id="adminTableAndPagination">
    <H1><?=$T->head?></H1>
    <div class="row">
      <div class="col">
        <admin-practic  :link="'/admin/practic/getData/?student=<?=$_GET['student'] ?? 'all'?>'"
                        :student="'<?=$_GET['student']?>'"></admin-practic>
      </div>
    </div>
  </div>
<?php include_once __DIR__ . '/../footer.php'; ?>