<?php

class ControllerAdmHelp extends Controller {

  public function run()
  {
    ServiceUsers::isUserAccess('admin');
    $t = Template::getInstance();
    $t->user = $_SESSION['user'];
    if (count($this->url) > 4) {
      $this->act404();
      return;
    }
    if (count($this->url) < 3) {
      $this->actList();
      return;
    }
    switch ($this->url[2]) {
      case 'list':
        $this->actList();
        return;
      case 'view':
        $this->actView();
        return;
      case 'delete':
        $this->actDelete();
        return;
      case 'save':
        $this->actSave();
        return;
      case 'searchStudent':
        $this->actListStudent();
        return;
      case 'getData':
        $this->getData();
        return;
    }
    if ($_GET['page']) {
      $this->actList();
      return;
    }
    if (isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    $this->act404();
  }

	private function actList()
  {

    $page = $_GET['page'] ?? 1;
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $t = Template::getInstance();
    $filter = $_GET['filter'] ?? 'all';
    $student = $_GET['student'] ?? 'all';
    $db = new ModelAdmHelp();
    $row = $db->getFilterData(
      $student,
      $filter,
      $art,
      );
    $t->head = 'Помощь';
    $nof = $db->getKol($student)['COUNT(*)'];
    $nofPage = ceil($nof/$kol);
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrum[1] = ['head' => 'Помощь', 'href' => ''];
    $this->addHtmCodeList($dataBreadcrum, $row, $page, $nofPage);
    $dbStudent = new ModelStudent();
    $t->students = $dbStudent->getData();
    include_once TEMPLATES . '/Adm/ViewAdmHelp.php';
  }

  private function actView()
  {
    $student = $_GET['student'];
    $id = (int)$_GET['id'];
    $t = Template::getInstance();
    $db = new ModelAdmHelp();
    $row = $db->getOne($id);
    $t->help = $row;
    $dataBreadcrum[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrum[1] = ['head' => 'Помощь', 'href' => '/admin/help/'];
    $dataBreadcrum[2] = ['head' => 'Просмотр ответа на вопрос', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    $t->RichEdit = ServiceHTMLCode::addRichEdit('ru');
    $t->navbar = ServiceHTMLCode::addNavbar();
    $t->form = ServiceHTMLCode::openForm('method="post" action="/admin/help/save/?student='.$student.'&id='.$id.'"');
    $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
    $t->form .= ServiceHTMLCode::addLable('for="comment"', 'Введите ответ на вопрос');
    $t->form .= ServiceHTMLCode::addFormTextArea('class="form-control textInput-fluid" name="answer" id="comment" placeholder="Ответ"', $row['answer']);
    $t->form .= ServiceHTMLCode::closeDiv();
    $t->form .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Отправить"');
    $t->head = 'Просмотр ответа на вопрос';
    include_once TEMPLATES . '/Adm/ViewAdmHelpText.php';
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
    $db = new ModelAdmHelp();
    if (strpos($filter, 'Answer')) {
      $row = $db->getFilterDataByAnswer($art, $filter, $student);
    } elseif (strpos($filter,'dateChange') === 0) {
      $row = $db->getFilterDataByOld($art, $filter, $student);
    } else {
      $row = $db->getData($art, $student);
    }
    /*$row = $db->getFilterData(
      $student,
      $filter,
      $art,
      );*/
    $nof = $db->getKol($student)['COUNT(*)'];
    $nofPage = ceil($nof/$kol);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'message' => $row[$i]['message'],
          'answer' => $row[$i]['answer'], 'student' => $row[$i]['student'],
          'whose' => $row[$i]['whose']
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }

  private function actSave()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && empty($_GET['student'])) {
      redirect('/admin/help/');
    }
    $form = new ServiceForm('help');
    $form->readData();
    $form->checkField('answer', 'string', true);
    $t = Template::getInstance();
    if ($form->isError() === true) {
      $t->errors = $form->getErrors();
      $t->data = $form->getData();
      redirect('/admin/help/view/?student=' . $_GET['student'] . '&id=' . $_GET['id']);
    }
    $id = $_GET['id'];
    $db = new ModelAdmHelp();
    $ok = $db->saveOne('help', 'answer', $id);
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    $url = '/admin/help/';
    redirect($url);
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
    $db = new ModelAdmHelp();
    $i = 0;
    while ($i <= count($id) - 1) {
      $ok = $db->deleteOne($id[$i]);
      $i++;
    }
    if ($ok !== true) {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = __METHOD__ . 'Ошибка записи базы данных';
      writeFile($str);
    }
    //$url = '/admin/help/';
    //redirect($url);
  }

  private function actSearch()
  {
    $search = $_GET['search'];
    $search = htmlspecialchars($search);
    $arraydata = explode(' ', $search);
    $db = new ModelAdmHelp();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'message' => $row[$i]['message'],
          'answer' => $row[$i]['answer'], 'student' => $row[$i]['student'],
          'whose' => $row[$i]['whose']
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
    $t->input = ServiceHTMLCode::addInput('id="studentAjax" type="text" list="cars" placeholder="ФИО студента"');
    $select[0] = ['atr' => 'onclick="setAttr(\'filter\',\'all\')" '. Selected('filter', 'all'), 'value' => 'Все'];
    $select[1] = ['atr' => 'onclick="setAttr(\'filter\',\'noHaveAnswer\')" '. Selected('filter', 'noHaveAnswer'), 'value' => 'Не отвеченные'];
    $select[2] = ['atr' => 'onclick="setAttr(\'filter\',\'haveAnswer\')" '. Selected('filter', 'haveAnswer'), 'value' => 'Отвеченные'];
    $select[3] = ['atr' => 'onclick="setAttr(\'filter\',\'dateChangeNew\')" '. Selected('filter', 'dateChangeNew'), 'value' => 'Свежие'];
    $select[4] = ['atr' => 'onclick="setAttr(\'filter\',\'dateChangeOld\')" '. Selected('filter', 'dateChangeOld'), 'value' => 'Старые'];
    $t->select = ServiceHTMLCode::addSelect('class="custom-select marginLeft"', $select);
    $t->form = ServiceHTMLCode::openForm('method="get" action="/admin/help/"');
    $searchData = '';
    $t->form .= ServiceHTMLCode::addInput('type="text" name="search" placeholder="Поиск" value="'.$searchData.'"');
    $t->form .= ServiceHTMLCode::addInput('type="submit" class="btn btn-primary" value="Поиск"');
    $t->form .= ServiceHTMLCode::closeForm();
    $t->formDeleteData = ServiceHTMLCode::openForm('method="post" action="/admin/help/delete/"');
    $t->formDeleteData .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Удалить" 
                                                              id="btn" disabled="disabled"');
    $t->table = $this->addTableHtml($row);
    $t->formDeleteDataClose .= ServiceHTMLCode::closeForm();
    $t->formDeleteDataClose .= ServiceHTMLCode::closeDiv();
    $t->pagination = ServiceHTMLCode::addPagination($page, $nofPage, '/admin/help/');
  }

  private function addTableHtml(array $row)
  {
    ServiceAddTable::addTable('class="table"');
    ServiceAddTable::addHead('class="thead-dark"');
    ServiceAddTable::addTR('');
    ServiceAddTable::addTH('class="thCheckbox"','<input type="checkbox" id="select_all">');
    ServiceAddTable::addTH('','Сообщение');
    ServiceAddTable::addTH('','Студент');
    ServiceAddTable::addTH('','Отвечено');
    ServiceAddTable::closeTR();
    ServiceAddTable::closeHead();
    ServiceAddTable::addBody('');
    $i = 0;
    while ($i <= count($row) - 1) {
      ServiceAddTable::addTR('');
      ServiceAddTable::addTD('', '<input type="checkbox" name="id[]" value= "'.$row[$i]['id'].'">');
      $href = ServiceHTMLCode::openA('href ="/admin/help/view/?student='.$row[$i]['whose'].'&id='.$row[$i]['id'].'"', $row[$i]['message']);
      ServiceAddTable::addTD('', $href);
      ServiceAddTable::addTD('', $row[$i]['student']);
      ServiceAddTable::addTD('', $row[$i]['answer']);
      ServiceAddTable::closeTR();
      $i++;
    }
    ServiceAddTable::closeBody();
    ServiceAddTable::closeTable();
    return ServiceAddTable::getData();
  }
}