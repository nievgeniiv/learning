<?php
$T = Template::getInstance();
include __DIR__ . '/header.php';
?>

  <body>
  <h1><?=$T->page_title?></h1>
  <p><?=$T->errors['payment_status']?></p>
  <p><?=$T->errors['mc_gross']?></p>
  <p><?=$T->errors['mc_currency']?></p>
  </body>

<?php include_once __DIR__ . '/footer.php'; ?>