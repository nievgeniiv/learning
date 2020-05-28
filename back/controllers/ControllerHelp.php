<?php
Class ControllerHelp extends Controller
{
  public function run()
  {
    ServiceUsers::isUserAccess('student');
    if (count($this->url) > 4){
      $this->act404();
      return;
    }
    switch ($this->url[1]) {
      case 'view':
        $this->actView();
        return;
      case 'save':
        $this->actSave();
        return;
      case 'getData':
        $this->getData();
        return;
    }
    if (isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    if (count($this->url) < 2 or isset($_GET['page'])) {
      $this->actList();
      return;
    }
    $this->act404();
  }

  private function actList()
  {
    $t = Template::getInstance();
    $dataBreadcrum[0] = ['head' => 'Помощь', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    $t->head = 'Помощь';
    include_once TEMPLATES . 'ViewHelp.php';
  }

  private function actView()
  {
    $t = Template::getInstance();
    $form = new ServiceForm('help');
    $form->readData();
    if ($form->isError() === true) {
      $t->errors = $form->getErrors();
      $t->dataHelp = $form->getData();
    } else {
      $db = new ModelHelp();
      $t->help = $db->getOne($_GET['id']);
      $t->head = 'Вопрос';
    }
    $dataBreadcrum[0] = ['head' => 'Помощь', 'href' => '/help/'];
    $dataBreadcrum[2] = ['head' => 'Просмотр ответа на вопрос', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrum);
    include_once TEMPLATES . 'ViewHelpText.php';
  }

  private function actSave()
  {
    $url = '/help/?page=1';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
      redirect($url);
    }
    $form = new ServiceForm('help');
    $form->readData();
    $form->checkField('message', 'string', true);
    if ($form->isError() === true) {
      $form->saveData();
      redirect($url);
    }
    $data = $form->getData();
    $db = new ModelHelp();
    $ok = $db->saveData($data['message'], $_SESSION['name'], (int)$_GET['id']);
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    redirect($url);
  }

  private function actSearch()
  {
    $dataSearch = $_GET['search'];
    $dataSearch = htmlspecialchars($dataSearch);
    $arraydata = explode(' ', $dataSearch);
    $db = new ModelHelp();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'message' => $row[$i]['message'],
          'answer' => $row[$i]['answer'], 'dataChange' => $row[$i]['dateChange'],
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => 1, 'nofPage' => 1 ];
    echo json_encode($data);
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
    $db = new ModelHelp();
    $row = $db->getFilterData(
      $_SESSION['name'],
      $filter,
      $art,
      );
    $nofPage = ceil($db->getKol($_SESSION['name'])['COUNT(*)']/$kol);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'message' => $row[$i]['message'],
          'answer' => $row[$i]['answer'], 'dataChange' => $row[$i]['dateChange'],
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }
}
