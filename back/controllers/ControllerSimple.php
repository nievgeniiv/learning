<?php


class ControllerSimple extends Controller
{

  /**
   * ControllerSimple constructor.
   */
  public function run()
  {
    ServiceUsers::isUserAccess('student');
    $t = Template::getInstance();
    switch ($this->url['1']){
      case '':
        $dir = __DIR__ . '/../../www/files';
        $t->listFiles = scandir($dir);
        require_once TEMPLATES . 'ViewSimple.php';
        return;
    }
  }
}