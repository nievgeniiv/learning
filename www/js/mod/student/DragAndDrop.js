$(document).ready(function() {

  let dropZone = $('#dropZone'),
    maxFileSize = 10000000; // максимальный размер фалйа - 10 мб.

  // Проверка поддержки браузером
  if (typeof(window.FileReader) == 'undefined') {
    dropZone.text('Не поддерживается браузером!');
    dropZone.addClass('error');
  }

  // Добавляем класс hover при наведении
  dropZone[0].ondragover = function() {
    dropZone.addClass('hover');
    return false;
  };

  // Убираем класс hover
  dropZone[0].ondragleave = function() {
    dropZone.removeClass('hover');
    return false;
  };

  // Обрабатываем событие Drop
  dropZone[0].ondrop = function(event) {
    event.preventDefault();
    dropZone.removeClass('hover');
    dropZone.addClass('drop');

    let i = 0;
    let length = event.dataTransfer.files.length;
    //цикл while необходим для отправки нескольких файлов
    while(i <= length - 1) {
      let file = event.dataTransfer.files[i];
      // Проверяем размер файла
      if (file.size > maxFileSize) {
        dropZone.text('Файл слишком большой!');
        dropZone.addClass('error');
        return false;
      }

      let form_data = new FormData();
      form_data.append('file', file);
      $.ajax({
        url: '/admin/ajax/load/?numb=' + i,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        dataType: 'json',
        type: 'post',
        xhr: function(){
          let xhr = $.ajaxSettings.xhr();
          // Устанавливаем обработчик подгрузки
                 xhr.addEventListener('progress', function(event){
                   let percent = Math.ceil(event.loaded / event.total * 100);
                   dropZone.val(percent).text('Загрузка: ' + percent + '%');
                 }, false);
                 return xhr;
               },
        success: function (data) {
          let dataPHP = data.md5;
          dropZone.text('Загрузка успешно завершена!');
          let data_split = dataPHP.split('.')[0];
          $('.loadFiles').append('<p id="' + data_split + '"><a href="/files/'+ data.fileName +'">'+ data.fileName +'</a>' +
            '<button class="delete" id="' + data_split + '">X</button></p>');
          //document.getElementById("text").innerHTML = data;
        }
      });
      i++;
    }
  };


  $('#load').click(function () {
    let url = '/admin/ajax/save/';
    jQuery.ajax({
      url: url,
      success: function () {
        location.reload();
      }
    });
  });


  $('.loadFiles').on('click','.delete',function () {
    let url = '/admin/ajax/delete/?name=' + this.id;
    jQuery.ajax({
      url: url,
      data: this.id,
      type: 'post',
      success: function (data) {
        data = data.split('.')[0];
        $('#' + data).remove();
        location.reload();
      }
    });
  });
});
