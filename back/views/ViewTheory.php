<?php
include_once __DIR__ . '/navbar.php';
?>

<?=$T->breadcrumb?>
<div class="container" id="adminTableAndPagination">
  <H1><?=$T->head?></H1>
  <theory :link="'/theory/getData/'"></theory>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>