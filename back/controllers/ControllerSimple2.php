<?php


class ControllerSimple2 extends Controller
{

  public function run()
  {
    switch ($this->url[1]) {
      case '':
        $this->actView();
        return;
      case 'save':
        $this->actSave();
        return;
    }
  }

  private function actView()
  {
    $t = Template::getInstance();
    $t->name = 'Данные из PHP';
    $t->age = 'Лет из PHP';
    include_once TEMPLATES . 'ViewSimple2.php';
  }

  private function actSave()
  {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    var_dump($data);
    if (isset($data['name'])) {
      echo 'ok';
    }
    if (!empty($data['name'])) {
      echo 'okName';
    }
  }


}