<?php
$T = Template::getInstance();
include TEMPLATES . 'header.php';
?>

  <link rel="stylesheet" href="/css/drag-and-drop.css">

  <script src="/lib/dist/jquery.js"></script>

  <script src="/js/DragAndDrop.js"></script>

<body>

<form id="form" action="/qwe/load/" enctype="multipart/form-data">
  <div id="dropZone">
    Для загрузки, перетащите файл сюда.
  </div>
</form>
<button id="load" name="qwe">Загрузить файлы</button>
<div class = "loadFiles">

</div>
<p><span id="text"></span></p>

</body>
<?php
include TEMPLATES . 'footer.php';