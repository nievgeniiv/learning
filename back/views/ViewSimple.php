<?php
include __DIR__ . '/navbar.php';
?>
<div class="container">
  <div class="row padding">
    <div class="col">
      <h5>Добавление полей с помощью нативного JS</h5>
      <p id="select_add">
      <select onchange="addElement(this.value);" id='scripts' class="custom-select">
        <option value="0">Выбрать элемент</option>
        <option value="1" id="date_add">Дата</option>
        <option value="2" id="text_area" onchange="addAreaText()">Область ввода текста</option>
        <option value="3" id="text_add_select" onchange="addText()">Текстовое поле</option>
      </select></p>
      <div id="add">
      </div>
    </div>
  </div>
  <div class="row padding">
    <div class="col">
      <h5>Добавление полей с помощью jQuery</h5>
      <p id="select_add_jQuery">
        <select class="custom-select">
          <option>Выбрать элемент</option>
          <option id="date_add_jQuery">Дата</option>
          <option id="text_area_jQuery">Область ввода текста</option>
          <option id="text_add_select_jQuery">Текстовое поле</option>
        </select>
      </p>
    </div>
  </div>
  <div class="row padding">
    <div class="col">
      <h1>Drag-and-drop</h1>
      <div class="row padding">
        <?php
          if (isset ($T->listFiles)) {
            echo '<p>Список файлов в папке files</p>';
            echo '<ul>';
            $i = 2;
            while ($i <= count($T->listFiles) - 1) {
              echo '<li>' . $T->listFiles[$i] . '</li>';
              $i++;
            }
            echo '</ul>';
          }
        ?>
      </div>
    </div>
  </div>
  <div class="row padding">
    <div class="col">
      <form id="form" enctype="multipart/form-data">
        <div id="dropZone">
          Для загрузки, перетащите файл сюда.
        </div>
      </form>
    </div>
  </div>
  <div class="row padding">
    <div class="col">
      <button id="load" name="loadFile" class="btn btn-primary">Загрузить файлы</button>
    </div>
  </div>
    <div class = "loadFiles">
    </div>
    <p><span id="text"></span></p>
</div>
<?php include_once __DIR__ . '/footer.php';?>
