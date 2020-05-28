<?php

Class ControllerPractic extends Controller {

  public function run()
  {
    ServiceUsers::isUserAccess('student');
    if (count($this->url) < 2 or isset($_GET['page'])) {
      $this->actList();
      return;
    }
    switch ($this->url[1]) {
      case 'list':
        $this->actList();
        return;
      case 'save':
        $this->actSave();
        return;
      case 'getData':
        $this->actGetData();
        return;
    }
    if (isset($_GET['id'])){
      $this->actView();
      return;
    }
    if (isset($_GET['search'])){
      $this->actSearch();
      return;
    }
    $this->act404();
  }

  private function actList()
  {
    $t = Template::getInstance();
    $t->head = 'Практика';
    include_once TEMPLATES . 'ViewPractic.php';
  }

  private function actView()
  {
    $t = Template::getInstance();
    $form = new ServiceForm('answer');
    $form->readData();
    if ($form->isError() === true) {
      $t->errors = $form->getErrors();
      $t->data = $form->getData();
    } else {
      $db = new ModelPractic();
      $t->dataPractic = $db->getOne($_GET['id']);
    }
    include_once TEMPLATES . 'ViewPracticText.php';
  }

  private function actSave()
  {
    $url = '/practic/';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['id'])) {
      redirect($url);
    }
    $id = (int)$_GET['id'];
    $form = new ServiceForm('answer');
    $form->readData();
    $form->checkField('answer', 'string', true);
    if ($form->isError() === true) {
      $form->saveData();
      redirect($url . '?id=' . $id);
    }
    $data = $form->getData();
    $db = new ModelPractic();
    $ok = $db->saveData($id, $data['answer']);
    if ($ok = true) {
      $form->clear();
    } else {
      $form->setValue('db', 'Ошибка записи в базу данных');
      $str = 'Ошибка записи базы данных';
      writeFile($str);
    }
    redirect($url);
  }

  public function actSearch()
  {
    $dataSearch = $_GET['search'];
    $dataSearch = htmlspecialchars($dataSearch);
    $arraydata = explode(' ', $dataSearch);
    $db = new ModelPractic();
    $row = $db->searchData($arraydata);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'theme' => $row[$i]['theme'], 'date' => $row[$i]['date'],
          'rating' => $row[$i]['rating'], 'dataChange' => $row[$i]['dateChange']
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => 1, 'nofPage' => 1 ];
    echo json_encode($data);
  }

  private function actGetData()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $filter = $_GET['filter'] ?? 'all';
    $student = $_GET['student'] ?? 'all';
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $db = new ModelPractic();
    if (strpos($filter, 'Rating')) {
      $row = $db->getFilterDataByRating($art, $filter, $student);
    } elseif (strpos($filter, 'Answer')) {
      $row = $db->getFilterDataByAnswer($art, $filter, $student);
    } elseif (strpos($filter,'dateChange') === 0) {
      $row = $db->getFilterDataByOld($art, $filter, $student);
    } else {
      $row = $db->getData($art, $student);
    }
    $nofPage = ceil($db->getKol($_SESSION['name'])['COUNT(*)']/$kol);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        [
          'identificate' => $row[$i]['id'], 'theme' => $row[$i]['theme'], 'date' => $row[$i]['date'],
          'rating' => $row[$i]['rating'], 'dataChange' => $row[$i]['dateChange']
        ]
      ];
      $i++;
    }
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }
}