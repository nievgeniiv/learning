<?php


Class ControllerAdmTheory extends Controller {

  public function run()
  {
    ServiceUsers::isUserAccess('admin');
    $t = Template::getInstance();
    $t->user = $_SESSION['user'];

    switch ($this->url[2]) {
      case 'edit':
        $this->actView();
        return;
      case 'save':
        $this->actSave();
        return;
      case 'delete':
        $this->actDelete();
        return;
      case 'getData':
        $this->actGetData();
        return;
    }
    if (isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    if (isset($_GET['page'])) {
      $this->actList();
      return;
    }
    $this->act404();
  }
  
  private function actList()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $t = Template::getInstance();
    $t->head = 'Теория';
    $dataBreadcrumb[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrumb[1] = ['head' => 'Теория', 'href' => ''];
    $db = new ModelAdmTheory();
    $maxNof = $db->getKol()['COUNT(*)'];
    $art = ($page * 6) - 6;
    $row = $db->getData($art);
    $this->addHtmlCodeList($dataBreadcrumb, $page, $row, $maxNof);
    include_once TEMPLATES . '/Adm/ViewAdmTheory.php';
  }

  private function actGetData()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $db = new ModelAdmTheory();
    $nofPage = (int)$db->getKol()['COUNT(*)'];
    $nofPage = ceil($nofPage / 6);
    $art = ($page * 6) - 6;
    $row = $db->getData($art);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        ['identificate' => $row[$i]['id'], 'name' => $row[$i]['theme']]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }

  private function actView()
  {
    $t = Template::getInstance();
    $form = new ServiceForm('theory');
    $form->readData();
    $t->head = 'Добавить новую теорию';
    if ($form->isError() === true) {
      $t->errors = $form->getErrors();
      $t->data = $form->getData();
    }
    if ((int)$_GET['id'] !== 0) {
      $i = $_GET['id'] - 1;
      $db = new ModelAdmTheory();
      $theory = $db->getData()[$i];
      $t->head = 'Редактировать теорию';
    } else {
      $theory = [];
    }
    $this->addHtmlCodeView($theory, $t->head);
    include_once TEMPLATES . '/Adm/ViewAdmTheoryAdd.php';
  }

  private function actSave()
  {
    $url = '/admin/theory/?page=1';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && empty($_GET['id'])) {
      redirect($url);
    }
    $form = new ServiceForm('AdmTheory');
    $form->readData();
    $this->inputData($form);
    if ($form->isError() === true) {
      $form->saveData();
      redirect($url . 'edit/?id=' . $_GET['id']);
    }
    $data = $form->getData();
    $id = $_GET['id'];
    $db = new ModelAdmTheory();
    if ($id === '0') {
      $ok = $db->saveData($data['theme'], $data['text'], $data['link'] ?? '');
    } else {
      $ok = $db->updateData($id, $data['theme'], $data['text'], $data['link'] ?? '');
    }
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    redirect($url);
  }

  private function actDelete()
  {
    $data = file_get_contents('php://input');
    $id =json_decode($data, true)['data'];
    $form = new ServiceForm('AdmTheory');
    $db = new ModelAdmTheory();
    $i = 0;
    while ($i <= count($id) - 1) {
      $ok = $db->deleteOne((int)$id[$i]);
      $i++;
    }
    if ($ok !== true) {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    //redirect('/admin/theory/?page=1');
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
    $db = new ModelAdmTheory();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        ['identificate' => $row[$i]['id'], 'name' => $row[$i]['theme']]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => 1, 'nofPage' => 1 ];
    echo json_encode($data);
  }

  private function inputData(ServiceForm $form)
  {
    $form->checkField('theme', 'string', true);
    $form->checkField('text', 'string', true);
    $form->checkField('link', 'string', false);
  }

  private function addHtmlCodeList(array $dataBreadcrumb, int $page, array $data, int $maxNof, string $searchData='')
  {
    $t = Template::getInstance();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
    $t->navbar = ServiceHTMLCode::addNavbar();
    /*$nofPage = ceil($maxNof/6);
    $t->pagination = ServiceHTMLCode::addPagination($page, $nofPage, '/admin/theory/');
    $t->form = ServiceHTMLCode::openForm('method="get" action="/admin/theory/"');
    $t->form .= ServiceHTMLCode::addInput('type="text" name="search" placeholder="Поиск" value="'.$searchData.'"');
    $t->form .= ServiceHTMLCode::addInput('type="submit" class="btn btn-primary" value="Поиск"');
    $t->form .= ServiceHTMLCode::closeForm();
    $t->button = ServiceHTMLCode::addButton('href="/admin/theory/edit/?id=0"', 'class="btn btn-primary"',
      'Добавить теорию');
    $t->formDeleteData = ServiceHTMLCode::openForm('method="post" action="/admin/theory/delete/"');
    $t->formDeleteData .= ServiceHTMLCode::openDiv('class="col offset-1"');
    $t->formDeleteData .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Удалить" 
                                                              id="btn" disabled="disabled"');
    $t->formDeleteData .= ServiceHTMLCode::closeDiv();
    $t->table = $this->addTableHtml($data);
    $t->formDeleteDataClose .= ServiceHTMLCode::closeForm();
    $t->formDeleteDataClose .= ServiceHTMLCode::closeDiv();*/
  }

  /*private function addTableHtml(array $data) : string
  {
    ServiceAddTable::addTable('class="table"');
    ServiceAddTable::addHead('class="thead-dark"');
    ServiceAddTable::addTR('');
    ServiceAddTable::addTH('class="thCheckbox"','<input type="checkbox" id="select_all">');
    ServiceAddTable::addTH('','Тема');
    ServiceAddTable::closeTR();
    ServiceAddTable::closeHead();
    ServiceAddTable::addBody('');
    $i = 0;
    while ($i <= count($data) - 1) {
      ServiceAddTable::addTR('');
      ServiceAddTable::addTD('', '<input type="checkbox" name="id[]" value= "'.$data[$i]['id'].'">');
      $href = ServiceHTMLCode::openA('href="/admin/theory/edit/?id=' . $data[$i]['id'] . '"', $data[$i]['theme']);
      ServiceAddTable::addTD('', $href);
      ServiceAddTable::closeTR();
      $i++;
    }
    ServiceAddTable::closeBody();
    ServiceAddTable::closeTable();
    return ServiceAddTable::getData();
  }*/

  private function addHtmlCodeView(array $theory, string $head)
  {
    $dataBreadcrumb[0] = ['head' => 'Домашняя страница', 'href' => '/admin/'];
    $dataBreadcrumb[1] = ['head' => 'Список тем по теории', 'href' => '/admin/theory/?page=1'];
    $dataBreadcrumb[2] = ['head' => $head, 'href' => ''];
    $t = Template::getInstance();
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
    $t->RichEdit = ServiceHTMLCode::addRichEdit('ru');
    $t->navbar = ServiceHTMLCode::addNavbar();
    $t->form = ServiceHTMLCode::openForm('method="post" action="/admin/theory/save/?id='.$_GET['id'].'"');
    $array[0] = ['atr' => 'theme', 'text' => 'Тема'];
    $array[1] = ['atr' => 'text', 'text' => 'Введите текст теории:'];
    $array[2] = ['atr' => 'link', 'text' => 'Введите ссылку:'];
    $i = 0;
    while ($i <= count($array) - 1) {
      $t->form .= ServiceHTMLCode::openDiv('class="form-group"');
      $t->form .= ServiceHTMLCode::addLable('for="'.$array[$i]['atr'].'"', '<b>'.$array[$i]['text'].'</b>');
      $t->form .= ServiceHTMLCode::addError($array[$i]['atr']);
      if ($i === 0) {
        $t->form .= ServiceHTMLCode::addInput('class="form-control textInput-fluid" type="text" 
                    id="'.$array[$i]['atr'].'" placeholder="'.$array[$i]['text'].'" name="'.$array[$i]['atr'].'" 
                    value="'.$theory['theme'].'"');
      } else {
        $t->form .= ServiceHTMLCode::addFormTextArea('class="form-control" id="text" 
                            placeholder="'.$array[$i]['text'].'" name="'.$array[$i]['atr'].'"',
          $theory['text'] ?? '');
      }
      $t->form .= ServiceHTMLCode::closeDiv();
      $i++;
    }
    $t->form .= ServiceHTMLCode::addInput('type="submit" class="btn btn-primary" value="Сохранить"');
    $t->form .= ServiceHTMLCode::closeForm();
  }


}