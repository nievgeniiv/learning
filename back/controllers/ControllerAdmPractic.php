<?php

Class ControllerAdmPractic extends Controller
{
  public function run()
  {
    ServiceUsers::isUserAccess('admin');
    $t = Template::getInstance();
    $t->user = $_SESSION['user'];
    $route = Route::getInstance();
    if ($route->nof > 5) {
      $this->act404();
      return;
    }
    // /adm/practic/ - список
    if ($route->nof < 3) {
      $this->actList();
      return;
    }
    // /adm/practic/view/?id=10 - текст одной практики
    // /adm/practic/edit/?id=0/10 - добавление или редактирование
    // POST /adm/practic/save/?id=0/10 - сохранение нового или редактируемого
    switch ($route->url[2]) {
      case 'list':
        $this->actList();
        return;
      case 'view':
        $this->actView();
        return;
      case 'save':
        switch ($route->url[3]){
          case 'view':
            $this->actSave(true);
            return;
          default:
            $this->actSave();
            return;
        }
      case 'delete':
        $this->actDelete();
        return;
      case 'edit':
        $this->actEdit();
        return;
      case 'searchStudent':
        $this->actListStudent();
        return;
      case 'getData':
        $this->getData();
        return;
    }
    if (isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    $this->act404();
  }

  public function actList()
  {
    $page = $_GET['page'] ?? 1;
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $t = Template::getInstance();
    $filter = $_GET['filter'] ?? 'all';
    $student = $_GET['student'] ?? 'all';
    $db = new ModelAdmPractic();
    $row = $db->getFilterData(
      $student,
      $filter,
      $art,
      );
    $t->head = 'Практика';
    $nof = $db->getKol($student)['COUNT(*)'];
    $nofPage = ceil($nof/$kol);
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrum[1] = ['head' => 'Практика', 'href' => ''];
    $this->addHtmCodeList($dataBreadcrum, $row, $page, $nofPage);
    $dbStudent = new ModelStudent();
    $t->students = $dbStudent->getData();
    include_once TEMPLATES . '/Adm/ViewAdmPractic.php';
  }

  public function actView()
  {
    $student = $_GET['student'];
    $id = (int)$_GET['id'];
    $t = Template::getInstance();
    $t->RichEdit = ServiceHTMLCode::addRichEdit('ru');
    $t->navbar = ServiceHTMLCode::addNavbar();
    $db = new ModelAdmPractic();
    $row = $db->getOne($id, $student);
    $t->practic = $row;
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrum[1] = ['head' => 'Практике', 'href' => '/admin/practic/'];
    $dataBreadcrum[2] = ['head' => 'Просмотр ответа по практике', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    $t->form = ServiceHTMLCode::openForm('method="post" action="/admin/practic/save/view/?student='.$student.'
                                            &id='.$id.'"');
    $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
    $t->form .= ServiceHTMLCode::addLable('for="comments"', 'Введите комментарий к ответу');
    $t->form .= ServiceHTMLCode::addFormTextArea('class="form-control textInput-fluid" name="comments" 
                                      id="comments" placeholder="Коментарий"', $row[0]['comments']);
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
    $t->form .= ServiceHTMLCode::addLable('for="comments"', 'Оценить ответ');
    $select[0] = ['atr' => 'value="5"', 'value' => '5'];
    $select[1] = ['atr' => 'value="4"', 'value' => '4'];
    $select[2] = ['atr' => 'value="3"', 'value' => '3'];
    $select[3] = ['atr' => 'value="2"', 'value' => '2'];
    $select[4] = ['atr' => 'value="1"', 'value' => '1'];
    $t->form .= ServiceHTMLCode::addSelect('class="custom-select marginLeft" name="rating"', $select);
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->form .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Отправить"');
    $t->link = ServiceHTMLCode::openA('href="/admin/practic/edit/?student='.$student.'&id='.$id.'"', 'Редактировать');
    $t->head = 'Просмотр ответа по практике';
    include_once TEMPLATES . '/Adm/ViewAdmPracticText.php';
  }

  private function getData()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $filter = $_GET['filter'] ?? 'all';
    $student = $_GET['student'] ?? 'all';
    $db = new ModelAdmPractic();
    if (strpos($filter, 'Rating')) {
      $row = $db->getFilterDataByRating($art, $filter, $student);
    } elseif (strpos($filter, 'Answer')) {
      $row = $db->getFilterDataByAnswer($art, $filter, $student);
    } elseif (strpos($filter,'dateChange') === 0) {
      $row = $db->getFilterDataByOld($art, $filter, $student);
    } else {
      $row = $db->getData($art, $student);
    }
    $nofPage = (int)$db->getKol($student)['COUNT(*)'];
    $nofPage = ceil($nofPage / 6);
    /*$row = $db->getFilterData(
      $student,
      $filter,
      $art,
      );*/
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'theme' => $row[$i]['theme'], 'student' => $row[$i]['student'],
          'date' => $row[$i]['date'], 'rating' => $row[$i]['rating'], 'whose' => $row[$i]['whose']
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }

  public function actEdit()
  {
    $t = Template::getInstance();
    $form = new ServiceForm('AdmPractic');
    $form->readData();
    $student = $_GET['student'];
    if ($form->isError() === true) {
      $t->practic = $form->getData();
      $t->parcticError = $form->getErrors();
    } else {
      $db = new ModelAdmPractic();
      $id = (int)$_GET['id'];
      if ($id !== 0) {
        $row = $db->getOne($id, $_GET['student'])[0];
      } else {
        $db = new ModelAdmPractic();
        $row = $db->getEmpty();
      }
    }
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrum[1] = ['head' => 'Практика', 'href' => '/admin/practic/list/'];
    $dataBreadcrum[2] = ['head' => 'Редактирование практики', 'href' => ''];
    $this->addHtmCodeEdit($dataBreadcrum, $row, $student, $id);
    include_once TEMPLATES . '/Adm/ViewAdmPracticAdd.php';
  }

  /**
   * @param bool $comments
   */
  public function actSave(bool $comments = false)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['id'])){
      redirect('/admin/practic/?student=' . $_GET['student'] . '&page=1');
    }
    $id = (int)$_GET['id'];
    $form = new ServiceForm('AdmPractic');
    $form->readData();
    $db = new ModelAdmPractic();
    $editUrl = '/admin/practic/list/?student=' . $_GET['student'] . '&page=1';
    if ($comments === true) {
      $form->checkField('comments', 'string', false);

    } else {
      $this->inputData($form, $db, $id);
      //$editUrl = '/admin/practic/edit/?student=' . $_GET['student'] . '&id=' . $id;
    }
    $t = Template::getInstance();
    if ($form->isError() === true){
      $form->saveData();
      //$form->clear();
      redirect($editUrl);
    }
    $data = $form->getData();
    $dbUsers = new ModelStudent();
    $shortName = $dbUsers->getOne($_GET['student']);
    if ($comments === false) {
      if ($id === 0) {
        $ok = $db->saveOne($data, $_GET['student'], $shortName[0]['shortName']);
      } else {
        $t->data = $data;
        $ok = $db->updateOne($id);
      }
    } else {
      $t->data = $data;
      $ok = $db->updateOne($id, true);
    }
    if ($ok !== true) {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    } else {
      $form->clear();
    }
    redirect($editUrl);
  }

  public function actListStudent()
  {
    $search = $_GET['item'];
    $search = htmlspecialchars($search);
    $arraydata = explode(' ', $search);
    $db = new ModelStudent();
    $row = $db->searchData($arraydata);
    if (empty($row)){
      echo '';
    } else {
      echo json_encode($row);
    }
  }

  private function actDelete()
  {
    $data = file_get_contents('php://input');
    $id =json_decode($data, true)['data'];
    $form = new ServiceForm('AdmPractic');
    $db = new ModelAdmPractic();
    $i = 0;
    while ($i <= count($id) - 1) {
      $ok = $db->deleteOne($id[$i]);
      //$ok = true;
      $i++;
    }
    if ($ok !== true) {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = __METHOD__ . 'Ошибка записи базы данных';
      writeFile($str);
    }
    $url = $_POST['URI'];
    //redirect($url);
  }

  /**
   * @param ServiceForm $form
   * @param ModelAdmPractic $db
   * @param int $id
   */
  public function inputData(ServiceForm $form, ModelAdmPractic $db, int $id)
  {
    $form->checkField('theme', 'string', 'true');
    $form->checkField('example', 'string', 'true');
    $form->checkField('task', 'string', 'true');
    //$form->checkField('captcha', 'captcha', 'true');
    //$form->checkField('recaptcha', 'recaptcha', 'true');
    $value = trim($form->getValue('theme'));
    $ok = $db->isExistByTheme($value, $id);
    if ($ok === false) {
      $form->setValue('theme', 'Такие данные уже существуют в базе данных');
    }
  }
  
  public function actSearch()
  {
    $search = $_GET['search'];
    if (empty($_GET['page'])) {
      $page = 0;
    } else {
      $page = (int)$_GET['page'] - 1;
    }
    $search = htmlspecialchars($search);
    $arraydata = explode(' ', $search);
    $db = new ModelAdmPractic();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'theme' => $row[$i]['theme'], 'student' => $row[$i]['student'],
          'date' => $row[$i]['date'], 'rating' => $row[$i]['rating'], 'whose' => $row[$i]['whose']
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => 1, 'nofPage' => 1 ];
    echo json_encode($data);
  }

  private function addHtmCodeList(array $dataBreadcrum, array $row, int $page, int $nofPage, string $searchData = '')
  {
    $t = Template::getInstance();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    $t->navbar = ServiceHTMLCode::addNavbar();
    /*$t->input = ServiceHTMLCode::addInput('id="studentAjax" type="text" list="cars" placeholder="ФИО студента"');
    $t->dataList = ' <datalist id="studentList"></datalist>';
    $select[0] = ['atr' => 'onclick="setAttr(\'filter\',\'all\')" '. Selected('filter', 'all'), 'value' => 'Все'];
    $select[1] = ['atr' => 'onclick="setAttr(\'filter\',\'noHaveRating\')" '. Selected('filter', 'noHaveRating'), 'value' => 'Не оцененные'];
    $select[2] = ['atr' => 'onclick="setAttr(\'filter\',\'haveRating\')" '. Selected('filter', 'haveRating'), 'value' => 'Оцененные'];
    $select[3] = ['atr' => 'onclick="setAttr(\'filter\',\'noHaveAnswer\')" '. Selected('filter', 'noHaveAnswer'), 'value' => 'Не отвеченные'];
    $select[4] = ['atr' => 'onclick="setAttr(\'filter\',\'haveAnswer\')" '. Selected('filter', 'haveAnswer'), 'value' => 'Отвеченные'];
    $select[5] = ['atr' => 'onclick="setAttr(\'filter\',\'dateChangeNew\')" '. Selected('filter', 'dateChangeNew'), 'value' => 'Свежие'];
    $select[6] = ['atr' => 'onclick="setAttr(\'filter\',\'dateChangeOld\')" '. Selected('filter', 'dateChangeOld'), 'value' => 'Старые'];
    $t->select = ServiceHTMLCode::addSelect('class="custom-select marginLeft"', $select);
    $t->form = ServiceHTMLCode::openForm('method="get" action="/admin/practic/"');
    $searchData = '';
    $t->form .= ServiceHTMLCode::addInput('type="text" name="search" placeholder="Поиск" value="'.$searchData.'"');
    $t->form .= ServiceHTMLCode::addInput('type="submit" class="btn btn-primary" value="Поиск"');
    $t->form .= ServiceHTMLCode::closeForm();
    if (empty($_GET['student']) or $_GET['student'] === 'all') {
      $disableButton = 'disabled';
      $text = 'Для добавления нового задания выберите студента';
    } else {
      $disableButton = '';
      $text = '';
    }
    $t->message = ServiceHTMLCode::openDiv('class="alert-wrning" role="alert"', $text);
    $t->message .= ServiceHTMLCode::closeDiv();
    $t->button1 = ServiceHTMLCode::addButton('href="/admin/practic/edit/?student='.$_GET['student'].'&id=0"', 'class="btn btn-primary" '.$disableButton, 'Добавить новое задание по практике');
    $t->formDeleteData = ServiceHTMLCode::openForm('method="post" action="/admin/practic/delete/"');
    $t->formDeleteData .= ServiceHTMLCode::openDiv('class="col offset-1"');
    $t->formDeleteData .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Удалить" 
                                                              id="btn" disabled="disabled"');
    $t->formDeleteData .= ServiceHTMLCode::closeDiv();
    //$t->table = $this->addTableHtml($row);
    $t->formDeleteDataClose .= ServiceHTMLCode::closeForm();
    $t->formDeleteDataClose .= ServiceHTMLCode::closeDiv();
    $t->pagination = ServiceHTMLCode::addPagination($page, $nofPage, '/admin/practic/');*/
  }

  /*private function addTableHtml(array $row)
  {
    ServiceAddTable::addTable('class="table"');
    ServiceAddTable::addHead('class="thead-dark"');
    ServiceAddTable::addTR('');
    ServiceAddTable::addTH('class="thCheckbox"','<input type="checkbox" id="select_all">');
    ServiceAddTable::addTH('','Тема');
    ServiceAddTable::addTH('','Студент');
    ServiceAddTable::addTH('','Оценка');
    ServiceAddTable::addTH('','Задание просрочено');
    ServiceAddTable::closeTR();
    ServiceAddTable::closeHead();
    ServiceAddTable::addBody('');
    $i = 0;
    while ($i <= count($row) - 1) {
      ServiceAddTable::addTR('');
      ServiceAddTable::addTD('', '<input type="checkbox" name="id[]" value= "'.$row[$i]['id'].'">');
      $href = ServiceHTMLCode::openA('href ="/admin/practic/view/?student='.$row[$i]['whose'].'&id='.$row[$i]['id'].'"', $row[$i]['theme']);
      ServiceAddTable::addTD('', $href);
      ServiceAddTable::addTD('', $row[$i]['student']);
      ServiceAddTable::addTD('', $row[$i]['rating']);
      if ($row[$i]['date'] < date('Y-m-d')) {
        $textTD = '<div class="textRed">Задание просрочено!</div>';
      } else {
        $textTD = '';
      }
      ServiceAddTable::addTD('', $textTD);
      ServiceAddTable::closeTR();
      $i++;
    }
    ServiceAddTable::closeBody();
    ServiceAddTable::closeTable();
    return ServiceAddTable::getData();
  }*/

  private function addHtmCodeEdit(array $dataBreadcrum, array $row, string $student, int $id)
  {
    $t = Template::getInstance();
    $t->RichEdit = ServiceHTMLCode::addRichEdit('ru');
    $t->navbar = ServiceHTMLCode::addNavbar();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    $t->form = ServiceHTMLCode::openForm('method="post" action="/admin/practic/save/?student='.$student.'&id='.$id.'"');
    $t->form .= ServiceHTMLCode::addFormInput('class="form-group"', 'for="theme"', 'Введите тему задания:', 'type="text" class="form-control" name="theme" 
                                id="theme" placeholder="Тема задания" value="'.$row['theme'].'"', 'theme');
    $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
    $t->form .= ServiceHTMLCode::addLable('for="example"', 'Введите текст примера');
    $t->form .= ServiceHTMLCode::addFormTextArea('class="form-control" name="example" 
                                id="example" placeholder="Текст примера"', $row['example'] ?? '');
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
    $t->form .= ServiceHTMLCode::addLable('for="task"', 'Введите текст задания');
    $t->form .= ServiceHTMLCode::addFormTextArea('class="form-control" name="task" 
                                id="task" placeholder="Текст задания"', $row['task'] ?? '');
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->form .= ServiceHTMLCode::addFormInput('class="form-group"', 'for="dateTask"', 'Выберите дату сдачи задания', 'type="text" class="form-control" name="date" 
                                id="dateTask" value="'.$row['date'].'"', 'date');
    $t->form .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Сохранить"');
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->head = 'Новое задание по практике';
  }
}