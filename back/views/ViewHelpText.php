<?php
include __DIR__ . '/navbar.php';
?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/help/?page=1">Помощь</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?=$T->head?></li>
    </ol>
  </nav>
  <div class="container">
    <H1><?=$T->head?></H1>
    <form action="/help/save/?id=<?=$_GET['id']?>" method="post">
      <div class="form-group">
        <label for="message">Введите свой вопрос:</label>
        <?= $T->help['message'] ?>
        <textarea class="form-control" name="message"><?= $T->help['answer'] ?></textarea>
      </div>
      <input type="submit" class="btn btn-primary" value="Отправить">
    </form>
</div>
<?php include_once __DIR__ . '/footer.php'; ?>