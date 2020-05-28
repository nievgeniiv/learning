<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 13.10.18
 * Time: 23:09
 */

include __DIR__ . '/header.php';

$T = Template::getInstance();


?>

  <body>
  <h1>Добро пожаловать на сайт по обучению веб-программированию</h1>
  <a href="/admin/theory/">Теория</a></br>
  <a href="/admin/practic/">Практика</a></br>
  <a href="/admin/help/">Помощь</a></br>
  <form action="/admin/practic/search" method="post">
    <input type="text" name="search">
    <input type="submit" value="Поиск">
  </form>

  <h3><?=$T->theme?></h3>
  <?php
  $i = 0;
  if (!empty($T->data['Theory'])){
    while ($i <= count($T->data['Theory'])-1){
      echo '<a href="/admin/theory/view/?id='.$T->data['Theory'][''.$i.'']['id'].'">'.$T->data['Theory'][''.$i.'']['theme'].'</a></br>';
      $i++;
    }
  }
  $i = 0;
  if (!empty($T->data['Practic'])) {
    while ($i <= count($T->data['Practic']) - 1) {
      echo '<a href="/admin/practic/view/?id=' . $T->data['Practic']['' . $i . '']['id'] . '">' . $T->data['Practic']['' . $i . '']['theme'] . '</a></br>';
      $i++;
    }
  }
  $i = 0;
  if (!empty($T->data['Help'])) {
    while ($i <= count($T->data['Help']) - 1) {
      echo '<a href="/admin/help/view/?id=' . $T->data['Help']['' . $i . '']['id'] . '">' . $T->data['Help']['' . $i . '']['message'] . '</a></br>';
      $i++;
    }
  }
  ?>
  </body>

<?php include_once __DIR__ . '/footer.php'; ?>