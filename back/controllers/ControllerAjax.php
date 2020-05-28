<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 12.03.19
 * Time: 23:19
 */

class ControllerAjax extends Controller
{

  public function run()
  {
    switch ($this->url[2]){
      case 'practic':
        $this->actView();
        return;
      case 'load':
        $this->actLoadFiles();
        return;
      case 'save':
        unset($_SESSION['files']);
        echo 'Файлы загружены!';
        return;
      case 'student':
        $this->actStudent();
        return;
      case 'simple':
        $this->actSimple();
        return;
      case 'delete':
        $this->actDeleteFiles();
        return;
      case 'page':
        $this->actPage();
        return;
    }
    $this->act404();
  }

  public function actView()
  {
    $t = Template::getInstance();
    $theme = $_GET['theme'];
    $id = $_GET['id'];
    $db = new ModelAdmPractic();
    $error = '';
    if ($db->isExistByTheme($theme, $id) === false){
      $error = ' Такая тема уже существует';
    }
    $t->ajax = $error;
  }

  public function actLoadFiles()
  {
    if (isset($_SESSION['files'])){
      $numb = count($_SESSION['files']);
    } else {
      $numb = 0;
    }
    $path = BACK . '/../www/files/';
    if (!isset($_SESSION['files'][$numb]) or $_SESSION['files'][$numb] === '') {
      $_SESSION['files'][$numb] = $_FILES['file'];
    }
    move_uploaded_file($_FILES['file']['tmp_name'], $path  . basename($_FILES['file']['name']));
    $result['md5'] = md5($_SESSION['files'][$numb]['name']);
    $result['fileName'] = $_SESSION['files'][$numb]['name'];
    echo json_encode($result);
    exit();
  }

  public function actDeleteFiles()
  {
    $dir = __DIR__ . '/../../www/files';
    $files = scandir($dir);
    $nameFile = strval($_GET['name']);
    $i = 0;
    while ($i <= count($files)) {
      if (md5($files[$i]) === $nameFile) {
        $path = BACK . '/../www/files/' . $files[$i];
        unlink($path);
      }
      $i++;
    }
    echo $nameFile;
  }

  private function actStudent()
  {
    $edit = $_GET['edit'];
    $db = new ModelStudent();
    $row = $db->getDataStudent($edit)[0];
    if ($row === null) {
      echo [];
    } else {
      echo $data = json_encode($row);
    }
  }

  private function actSimple()
  {
    /*if (empty($_GET['page'])) {
      $hatData[1] = [
        'identificate' => '1a', 'name' => 'Ни Евгений Вячеславович',
      ];
      $hatData[2] = [
        'identificate' => '2a', 'name' => 'Ни Роман Вячеславович'
      ];
      echo $hatData = json_encode($hatData);
      return;
    }
    $hatData[1] = [
      'identificate' => '1a', 'name' => 'Ни Роман Вячеславович'
    ];
    $hatData[2] = [
      'identificate' => '2a', 'name' => 'Ни Евгений Вячеславович'
    ];
    echo $hatData = json_encode($hatData);
    return;*/
    //TODO: реализовать отдачу массива с данными о студентах и страницами. Распарсить это во Vue
    return;
  }

  private function actPage()
  {
    $pages = [ 'page'=>3, 'nofPage'=>6 ];
    echo json_encode($pages);
  }
}