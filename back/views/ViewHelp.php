<?php
include __DIR__ . '/navbar.php';
?>
  <?=$T->breadcrumb?>
    <div class="container" id="adminTableAndPagination">
      <H1><?=$T->head?></H1>
      <div class="row padding">
        <div class="col">
          <help :link="'/help/getData/'"></help>
        </div>
      </div>
    </div>
<?php include_once __DIR__ . '/footer.php'; ?>