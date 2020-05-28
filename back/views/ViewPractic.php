<?php
include __DIR__ . '/navbar.php';
?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><?=$T->head?></li>
    </ol>
  </nav>
  <div class="container" id="adminTableAndPagination">
    <H1><?=$T->head?></H1>
    <div class="row padding">
      <div class="col">
        <practic :link="'/practic/getData/'"></practic>
      </div>
    </div>
  </div>

<?php include_once __DIR__ . '/footer.php'; ?>