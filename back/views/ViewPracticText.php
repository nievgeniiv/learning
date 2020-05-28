<?php
include __DIR__ . '/navbar.php';
?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item " aria-current="page"><a href="/practic/?page=1">Список тем по практике</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?=$T->dataPractic['theme']?></li>
    </ol>
  </nav>
<div class="container padding">
  <h1><?= $T->dataPractic['theme'] ?></h1>
  <div class="row padding">
    <div class="col">
      <h4>Пример</h4>
      <p><?= $T->dataPractic['example'] ?></p>
      <h4>Задание</h4>
      <p><?= $T->dataPractic['task'] ?></p>
      <form action="/practic/save/?id=<?= $T->dataPractic['id'] ?>" method="post">
        <div class="form-group">
          <label for="answer">Введите ответ или ссылку на bitbucket с ответом:</label>
          <?php if (isset($T->errors['answer'])) {echo '<p>' . $T->errors['answer'] . '</p>';}?>
          <input class="form-control" type="text" name="answer" placeholder="Ответ"
                value="<?php echo $T->data['answer'] ?? $T->dataPractic['answer'];?>">
        </div>
        <p><input type="submit" class="btn btn-primary" value="Отправить"></p>
      </form>

      <h4>Комментарии</h4>
      <p><?= $T->dataPractic['comments'] ?></p>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>