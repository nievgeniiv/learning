<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 14.10.18
 * Time: 1:08
 */

include __DIR__ . '/navbarAdm.php';
?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/">Домашняя страница</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?=$T->breadcrumb?></li>
    </ol>
  </nav>

  <div class="container">
    <H1><?=$T->head?></H1>
    <form action="/admin/?stud=new" method="post">
      <div class="form-group">
        <div class="form-group">
          <label for="name">ФИО (полностью)</label>
          <?php if (isset($T->errors['name'])) {echo '<p>' . $T->errors['name'] . '</p>';}?>
          <input class="form-control" type="text" id="name" placeholder="Логин"
                 name="name" value="<?php if (isset($T->data['name'])) {echo $T->data['name'];}?>">
          </div>
        </div>
        <div class="form-group">
          <label for="shortName">ФИО (инициалы)</label>
          <?php if (isset($T->errors['shortName'])) {echo '<p>' . $T->errors['shortName'] . '</p>';}?>
          <input class="form-control" type="text" id="shortName" placeholder="ФИО (инициалы)"
                 name="shortName" value="<?php if (isset($T->data['shortName'])) {echo $T->data['shortName'];}?>" required>
        </div>
        <div class="form-group">
          <label for="user">Логин</label>
          <?php if (isset($T->errors['user'])) {echo '<p>' . $T->errors['user'] . '</p>';}?>
          <input class="form-control" type="text" id="user" placeholder="Логин"
                 name="user" value="<?php if (isset($T->data['user'])) {echo $T->data['user'];}?>" required>
        </div>
        <div class="form-group">
          <label for="passwd">Пароль</label>
          <?php if (isset($T->errors['passwd'])) {echo '<p>' . $T->errors['passwd'] . '</p>';}?>
          <input class="form-control" type="text" id="passwd" placeholder="Пароль"
                 name="passwd" value="<?php if (isset($T->errors['passwd'])) {echo $T->data['passwd'];}?>"
                    <?php
                      if ($T->head === 'Создать нового студента') {
                        echo 'required';
                      }
                    ?>
                  >
        </div>
        <div class="form-group">
          <label for="email">e-mail</label>
          <?php if (isset($T->errors['email'])) {echo '<p>' . $T->errors['email'] . '</p>';}?>
          <input class="form-control" type="text" id="email" placeholder="e-mail"
                 name="email" value="<?php if (isset($T->data['email'])) {echo $T->data['email'];}?>" required>
        </div>
        <div class="form-group">
          <label for="email-replay">Повторите e-mail</label>
          <?php if (isset($T->errors['email-replay'])) {echo '<p>' . $T->errors['email-replay'] . '</p>';}?>
          <input class="form-control" type="text" id="email-replay" placeholder="Повторите e-mail"
                 name="email-replay" value="<?php if (isset($T->data['email']))
                                              {echo $T->data['email'];}?>" required>
        </div>
        <input type="submit" class="btn btn-primary" value="Сохранить изменения">
    </div>
    </form>
  </div>
<!--
  <div id="toReplace">
    <div v-bind:is="currentComponent"></div>
    <div v-show="!currentComponent" v-for="component in componentsArray">
      <button v-on:click="swapComponent(component)">{{ component }}</button>
    </div>
  </div> -->

  <div id="simple">
    <div v-bind:is="currentComponent"></div>
    <div v-show="!currentComponent"><p>name</p></div>
    <div v-show="!currentComponent" v-for="component in inputComponent">
      <button v-on:click="swapComponent(component)">{{ component }}</button>
    </div>
  </div>





<?php include_once TEMPLATES . 'footer.php'; ?>