<?php

include TEMPLATES . 'header.php';
?>
<?=$T->navbar?>
<?=$T->breadcrumb?>
<div class="container">
  <H1><?=$T->head?></H1>
  <?=$T->form?>
</div>
<!--<a href="https://oauth.vk.com/authorize?client_id=6906248&display=page&redirect_uri=http://192.168.0.102/login/vk/&scope=friends&response_type=code&v=5.92" title="_blank">Зайти через ВКонтакте</a>-->
<?php include TEMPLATES . 'footer.php'; ?>