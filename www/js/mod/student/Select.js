function addElement(value) {
  switch (value) {
    case '1':
      addDate();
      break;
    case '2':
      addAreaText();
      break;
    case '3':
      addText();
      break;
  }
}

function addDate()
{
  let Element = document.getElementById("date_div");
  if (Element === null) {
    add.insertAdjacentHTML('afterbegin', '<div class="row padding" id="date_div">' +
      '<div class="col"> <input type="text" id="date" name="date" placeholder="Дата сдачи задания" /></div>' +
      '</div>');
    $(function () {
      $("#date").flatpickr({
        "local": "ru",
        enableTime: false,
        allowInput: true,
        dateFormat: "d.m.Y",
      });
    });
    document.getElementById('scripts').value = '0';
  } else {
    Element.parentNode.removeChild(Element);
    document.getElementById('scripts').value = '0';
  }
}

function addAreaText()
{
  Element = document.getElementById("Add_text_div");
  if (Element === null) {
    add.insertAdjacentHTML('afterbegin', '<div class="row padding" id="Add_text_div">' +
      '<div class="col"><textarea name="additional_information" id="Add_text"></textarea></div>' +
      '</div>');
    document.getElementById('scripts').value = '0';
  } else {
    Element.parentNode.removeChild(Element);
    document.getElementById('scripts').value = '0';
  }
}

function addText()
{
  Element = document.getElementById("text_add_div");
  if (Element === null) {
    add.insertAdjacentHTML('afterbegin', '<div class="row padding" id="text_add_div">' +
      '<div class="col"><input type="text" id="text_add_jQuery" name="add_text" /></div></div>');
    document.getElementById('scripts').value = '0';
  } else {
    Element.parentNode.removeChild(Element);
    document.getElementById('scripts').value = '0';
  }
}