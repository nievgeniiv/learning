<?php
include_once  __DIR__ . '/navbar.php';
?>
  <?=$T->breadcrumb?>
  <div class="container">
    <H1><?= $T->theme ?></H1>
    <div class="row">
      <div class="col">
        <p><?= $T->text ?></p>
        <?php
        echo '<a href="https://'.$T->link.'">'.$T->link.'</a>';
        ?>
      </div>
    </div>
  </div>

<?php include_once __DIR__ . '/footer.php'; ?>