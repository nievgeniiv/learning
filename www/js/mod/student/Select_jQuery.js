$(function() {
  /*$('#Add').click(
    function () {
      if ($("textarea").is("#Add_text")) {
        $('#Add_text').remove();
      } else {
        $('#Add_p').after('<textarea name="additional_information" id="Add_text"></textarea>');
      }
    });*/

  $('#date_add_jQuery').click(
    function () {
      if ($("input").is("#date_jQuery")) {
        $('#date_jQuery_div').remove();
      } else {
        $('#select_add_jQuery').after('<div class="row padding" id="date_jQuery_div">' +
          '<div class="col"> <input type="text" id="date_jQuery" name="date" placeholder="Дата сдачи задания" /></div>' +
          '</div>');
        $(function () {
          $("#date_jQuery").flatpickr({
            "locale": "ru",
            enableTime: false,
            allowInput: true,
            dateFormat: "d.m.Y",
          });
        });
      }
    });

  $('#text_area_jQuery').click(
    function () {
      if ($("textarea").is("#Add_text_jQuery")) {
        $('#Add_text_jQuery_div').remove();
      } else {
        $('#select_add_jQuery').after('<div class="row padding" id="Add_text_jQuery_div">' +
          '<div class="col"><textarea name="additional_information" id="Add_text_jQuery"></textarea></div>' +
          '</div>');
      }
    });

  $('#text_add_select_jQuery').click(
    function () {
      if ($("input").is("#text_add_jQuery")) {
        $('#text_add_jQuery_div').remove();
      } else {
        $('#select_add_jQuery').after('<div class="row padding" id="text_add_jQuery_div">' +
          '<div class="col"><input type="text" id="text_add_jQuery" name="add_text" /></div></div>');
      }
    });

  $(function () {
    $("#dateTask").flatpickr({
      "locale": 'ru',
      enableTime: false,
      allowInput: true,
      dateFormat: "d.m.Y",
    });
  })
});