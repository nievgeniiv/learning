<?php
$T = Template::getInstance();
include __DIR__ . '/header.php';
?>

<body>
<div class="jumbotron padding">
  <h1><?=$T->errors?></h1>
</div>
<?php include_once __DIR__ . '/footer.php'; ?>
