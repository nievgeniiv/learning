<?php

Class ControllerTheory extends Controller {

  public function run()
  {
    ServiceUsers::isUserAccess('student');
    if (isset($_GET['id'])) {
      $this->actView();
      return;
    }
    if(isset($_GET['search'])) {
      $this->actSearch();
      return;
    }
    switch ($this->url[1]) {
      case 'getData':
        $this->actGetData();
        return;
    }
    if (isset($_GET['page']) or count($this->url) < 2) {
      $this->actList();
      return;
    }
    $this->act404();
  }

  private function actList()
  {
    $t = Template::getInstance();
    $t->head = 'Список тем по теории';
    $dataBreadcrumb[0] = ['head' => 'Список тем по теории', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
    include_once TEMPLATES . 'ViewTheory.php';
  }

  private function actView()
  {
    $t = Template::getInstance();

    $db = new ModelTheory();
    $row = $db->getOne($_GET['id']);
    $t->theme = $row['theme'];
    $t->text = $row['text'];
    $t->link = $row['link'];
    $dataBreadcrumb[0] = ['head' => 'Список тем по теории', 'href' => '/theory/'];
    $dataBreadcrumb[1] = ['head' => $row['theme'], 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($dataBreadcrumb);
    include_once TEMPLATES . 'ViewTheoryText.php';
  }

  private function actSearch()
  {
    $dataSearch = $_GET['search'];
    $dataSearch = htmlspecialchars($dataSearch);
    $arraydata = explode(' ', $dataSearch);
    $db = new ModelTheory();
    $t = Template::getInstance();
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

  private function actGetData()
  {
    if (empty($_GET['page'])) {
      $page = 1;
    } else {
      $page = (int)$_GET['page'];
    }
    $kol = 6;
    $art = ($page * $kol) - $kol;
    $db = new ModelTheory();
    $row = $db->getData($art);
    $i = 0;
    $data['dataTable'] = [];
    while ($i <= count($row) - 1) {
      $data['dataTable'] += [$i =>
        ['identificate' => $row[$i]['id'], 'name' => $row[$i]['theme']]
      ];
      $i++;
    }
    $nofPage = ceil($db->getKol()['COUNT(*)']/$kol);
    $data['pages'] = [ 'page' => $page, 'nofPage' => $nofPage ];
    echo json_encode($data);
  }
}