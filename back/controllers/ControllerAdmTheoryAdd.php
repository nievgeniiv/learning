<?php

Class ControllerAdmTheoryAdd extends Controller {

  private function act_theory_add(){
    include_once __DIR__ . '/../views/Adm/ViewAdmTheoryAdd.php';
  }

  private function act_theory_save($id){
    $id = $id + 1;
    $db = new ModelAdmTheory();
    $db->save_theory($id);
    $new_url = '/admin/theory/';
    redirect($new_url);
  }

  public function run(){
    $t = Template::getInstance();
    $id_max = count($t->theory);
    if (explode('/', $this->url->url)[3] === 'save'){
      $this->act_theory_save($id_max);
    }
    $this->act_theory_add();
  }
}