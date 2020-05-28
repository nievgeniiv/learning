<?php

Class ControllerAdm extends Controller {

  public function run()
  {

    //Если не совпадают права доступа то redirect и выводим ошибку
    //Если GET['stud'] = 'new' и есть $_POST['login'], $_POST['FIO'],  $_POST['passwd'] и $_POST['e-mail'] создаем нового студента
    //Если нет одного из POST то redirect и выводим ошибку
    ServiceUsers::isUserAccess('admin');
    switch ($this->url[1]) {
      case 'update':
        $this->actUpdate();
        return;
      case 'getData':
        $this->actGetData();
        return;
      case 'save':
        $this->saveData();
        return;
      case 'delete':
        $this->deleteData();
        return;
    }
    if (isset($_GET['new']) || isset($_GET['edit'])) {
      $this->actEdit();
      return;
    }
    if (isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    if (empty($_GET['stud'])) {
      $this->actView();
      return;
    }
    $this->act404();
    return;
  }

	private function actView()
  {
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    if (empty($_GET['page'])) {
      $page = 0;
    } else {
      $page = (int)$_GET['page'] - 1;
    }
    $t = Template::getInstance();
    $t->head = 'Домашняя страница';
    $db = new ModelStudent();
    $data = $db->getData($page);
    $this->addHtmlCodeView($dataBreadcrum, $page, $data, $db->getKol()['COUNT(*)']);
    include_once TEMPLATES . '/Adm/ViewAdmin.php';
	}

  private function actEdit()
  {
    $form = new ServiceForm('stud');
    $form->readData();
    $t = Template::getInstance();
    if ($form->isError() === true) {
      $t->errors = $form->getErrors();
      $t->data = $form->getData();
    }
    $dataBreadcrumb[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    if (isset($_GET['edit'])) {
      $dataBreadcrumb[1] = ['head' => 'Редактирование данных студента', 'href' => ''];
      $t->head = 'Редактировать данные студента';
        $db = new ModelStudent();
        $row = $db->getDataStudent($_GET['edit'])[0];
    }
    if (isset($_GET['new'])) {
      $dataBreadcrumb[1] = ['head' => 'Добавление нового студента', 'href' => ''];
      $t->head = 'Создать нового студента';
      $row = [];
    }
    $this->addHtmlCodeEdit($dataBreadcrumb, $row);
    include_once TEMPLATES . '/Adm/ViewAdminEdit.php';
  }

  private function actGetData()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $db = new ModelStudent();
    $students = $db->getData($art);
    $nofPage = ceil($db->getKol()['COUNT(*)']/$kol);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($students) - 1) {
      $data['dataTable'] += [$i =>
          ['identificate' => $students[$i]['identificate'], 'name' => $students[$i]['name']]
        ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }

  private function actUpdate()
  {
    writeFile('ok');
    foreach ($_POST as $key => $value) {
      writeFile();
    }
    $url = '/admin/';
    $form = new ServiceForm('stud');
    $form->readData(true);
    $this->inputData($form, true);
    if ($form->isError() === true) {
      $form->saveData();
      redirect($url);
    }
    $data = $form->getData();
    $db = new ModelAdmin();
    $ok = $this->updateData($db, $data, $_GET['edit']);
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    echo 'Данные успешно сохранены';
  }

	private function saveData()
  {
    $url = '/admin/';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      //redirect($url);
    }
    $form = new ServiceForm('stud');
    $form->readData(true);
    $this->inputData($form);
    if ($form->isError() === true) {
      $form->saveData();
      //redirect($url);
    }
    $data = $form->getData();
    $db = new ModelAdmin();
    $ok = $db->saveData($data['user'], $data['name'],  $data['passwd'], $data['email'], $data['shortName']);
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    //redirect($url);
  }

  private function deleteData()
  {
    $data = file_get_contents('php://input');
    $idetificate =json_decode($data, true)['data'];
    $db = new ModelAdmin();
    $i = 0;
    while ($i <= count($idetificate) - 1) {
      $ok = $db->deleteOne($idetificate[$i]);
      $i++;
    }
    $form = new ServiceForm('stud');
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    //redirect($url);
  }

  private function actSearch()
  {
    $search = $_GET['search'];
    if (empty($_GET['page'])) {
      $page = 0;
    } else {
      $page = (int)$_GET['page'] - 1;
    }
    $search = htmlspecialchars($search);
    $arraydata = explode(' ', $search);
    $db = new ModelStudent();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        ['identificate' => $row[$i]['identificate'], 'name' => $row[$i]['name']]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => 1, 'nofPage' => 1 ];
    echo json_encode($data);
  }

  private function inputData(ServiceForm $form, bool $vue = false)
  {
    if ($vue = false) {
      $form->checkField('user', 'string', true);
      $form->checkField('name', 'FIO', true);
      $form->checkField('passwd', 'string', true);
      $form->checkField('email', 'e-mail', true);
      return;
    }
    if (isset($form->name)) {
      $form->checkField('name', 'string', true);
    }
    if (isset($form->shortName)) {
      $form->checkField('shortName', 'string', true);
    }
    if (isset($form->passwd)) {
      $form->checkField('passwd', 'string', true);
    }
    if (isset($form->user)) {
      $form->checkField('user', 'string', true);
    }
    if (isset($form->email)) {
      $form->checkField('email', 'string', true);
    }
  }

  private function updateData(ModelAdmin $db, array $data, string $identificate) : bool
  {
    if (isset($data['name'])) {
      $db->updateData('name', $data['name'], $identificate, true);
    }
    if (isset($data['shortName'])) {
      $db->updateData('shortName', $data['shortName'], $identificate);
    }
    if (isset($data['user'])) {
      $db->updateData('user', $data['user'], $identificate);
    }
    if (isset($data['email'])) {
      $db->updateData('email', $data['email'], $identificate);
    }
    return true;
  }

  private function addHtmlCodeEdit(array $dataBreadcrumb, array $row)
  {
    $t = Template::getInstance();
    $t->button1 = ServiceHTMLCode::addButtonNoA(
      'class="btn btn-primary" v-show="!show" v-on:click="editForm(\''.$_GET['edit'].'\')"',
      'Редактировать');
    $t->button2 = ServiceHTMLCode::addButtonNoA(
      'class="btn btn-primary" v-show="show" v-on:click="show=null"','Отменить');
    $t->form = ServiceHTMLCode::openForm('@submit.prevent="submitForm"');
    $array[0] = ['atr' => 'name', 'type' => 'text', 'text' => 'ФИО (полностью)'];
    $array[1] = ['atr' => 'shortName', 'type' => 'text', 'text' => 'ФИО (инициалы)'];
    $array[2] = ['atr' => 'user', 'type' => 'text', 'text' => 'Логин'];
    $array[3] = ['atr' => 'passwd', 'type' => 'paswword', 'text' => 'Пароль'];
    $array[4] = ['atr' => 'email', 'type' => 'text', 'text' => 'Введите email'];
    $array[5] = ['atr' => 'email_replay', 'type' => 'text', 'text' => 'Повторите e-mail'];
    $i = 0;
    while ($i <= count($array) - 1) {
      $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
      $t->form .= ServiceHTMLCode::addLable('for="'.$array[$i]['atr'].'"', '<b>'.$array[$i]['text'].'</b>');
      $t->form .= ServiceHTMLCode::addError($array[$i]['atr']);
      $t->form .= ServiceHTMLCode::addParagraph('v-show="!show"', $row[$array[$i]['atr']] ?? '');
      $t->form .= ServiceHTMLCode::addInput('v-show="show" v-model="'.$array[$i]['atr'].'" class="form-control" 
                      type="'.$array[$i]['type'].'" id="'.$array[$i]['atr'].'" placeholder="'.$array[$i]['text'].'"
                       name="'.$array[$i]['atr'].'" value="{{'.$array[$i]['atr'].'}}"');
      $t->form .= ServiceHTMLCode::closeDiv();
      $i++;
    }
    $t->form .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" v-show="show" 
                                    v-on:click="edit=\''.$_GET['edit'].'\'" value="Сохранить изменения"');
    $t->form .= ServiceHTMLCode::closeForm();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
  }

  private function addHtmlCodeView(array $dataBreadcrumb, int $page, array $data, int $maxNof, string $searchData='')
  {
    $t = Template::getInstance();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
    $t->navbar = ServiceHTMLCode::addNavbar();
    $nofPage = ceil($maxNof/6);
    $t->pagination = ServiceHTMLCode::addPagination($page, $nofPage, '/admin/');
    $t->form = ServiceHTMLCode::openForm('method="get" action="/admin/"');
    $t->form .= ServiceHTMLCode::addInput('type="text" name="search" placeholder="Поиск" value="'.$searchData.'"');
    $t->form .= ServiceHTMLCode::addInput('type="submit" class="btn btn-primary" value="Поиск"');
    $t->form .= ServiceHTMLCode::closeForm();
    $t->button = ServiceHTMLCode::addButton('href="/admin/?new"', 'class="btn btn-primary"',
                                            'Добавить студента');
    $t->formDeleteData = ServiceHTMLCode::openForm('method="post" action="/admin/?delete=ok"');
    $t->formDeleteData .= ServiceHTMLCode::openDiv('class="col offset-1"');
    $t->formDeleteData .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Удалить" 
                                                              id="btn" disabled="disabled"');
    $t->formDeleteData .= ServiceHTMLCode::closeDiv();
    $t->table = $this->addTableHtml($data);
    $t->formDeleteDataClose .= ServiceHTMLCode::closeForm();
    $t->formDeleteDataClose .= ServiceHTMLCode::closeDiv();
  }

  private function addTableHtml(array $data) : string
  {
    ServiceAddTable::addTable('class="table"');
    ServiceAddTable::addHead('class="thead-dark"');
    ServiceAddTable::addTR('');
    ServiceAddTable::addTH('class="thCheckbox"','<input type="checkbox" id="select_all">');
    ServiceAddTable::addTH('','ФИО студента');
    ServiceAddTable::closeTR();
    ServiceAddTable::closeHead();
    ServiceAddTable::addBody('');
    $i = 0;
    while ($i <= count($data) - 1) {
      ServiceAddTable::addTR('');
      ServiceAddTable::addTD('', '<input type="checkbox" name="id[]" value= "'.$data[$i]['identificate'].'">');
      $href = ServiceHTMLCode::openA('href="/admin/?edit=' . $data[$i]['identificate'] . '"', $data[$i]['name']);
      ServiceAddTable::addTD('', $href);
      ServiceAddTable::closeTR();
      $i++;
    }
    ServiceAddTable::closeBody();
    ServiceAddTable::closeTable();
    return ServiceAddTable::getData();
  }
}