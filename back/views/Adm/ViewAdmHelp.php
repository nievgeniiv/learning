<?php
include_once TEMPLATES . 'header.php';
?>
<?=$T->navbar?>
<?=$T->breadcrumb?>
  <div class="container" id="adminTableAndPagination">
    <H1><?=$T->head?></H1>
    <div class="row">
      <div class="col">
        <admin-help :link="'/admin/help/getData/?student=<?=$_GET['student'] ?? 'all'?>'"></admin-help>
      </div>
    </div>
  </div>
<?php include_once __DIR__ . '/../footer.php'; ?>