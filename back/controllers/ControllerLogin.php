<?php

class ControllerLogin extends Controller
{

  public function run()
  {
    if (count($this->url) < 2) {
      $this->actView();
      return;
    }
    switch ($this->url[1]){
      case 'do':
        $this->actLogin();
        return;
      case 'vk':
        $this->actVKlogin();
        return;
      case 'out':
        $this->actOut();
        return;
    }
    $this->act404();
  }
  
	private function actView()
  {
    if (isset($_SESSION['user']) and isset($_SESSION['access']) and isset($_SESSION['name'])) {
      if ($_SESSION['access'] === 'admin') {
        $url = $_SESSION['url'] ?? '/admin/';
        redirect($url);
        return;
      }
      $url = $_SESSION['url'] ?? '/theory/?page=1';
      redirect($url);
    }
		$t = Template::getInstance();
    $t->navbar = ServiceHTMLCode::addNavbar();
    $data[0] = ['head' => 'Вход в систему', 'href' => ''];
    $t->breadcrumb = ServiceHTMLCode::addBreadcrum($data);
    $t->head = 'Вход в систему';
    $t->form = ServiceHTMLCode::openForm('action="/login/do/" method="post"');
    $valueUserName = $t->dataView['username'] ?? '';
    $t->form .= ServiceHTMLCode::addFormInput('class="form-group"', 'for="username"', 'Логин',
      'type="text" class="form-control" id="username" name="username" value="'.$valueUserName.'" 
      placeholder="ФИО пользователя" required', 'username');
    $valuePasswd = $t->dataView['passwd'] ?? '';
    $t->form .= ServiceHTMLCode::addFormInput('class="form-group"', 'for="passwd"', 'Пароль',
      'type="password" class="form-control" id="passwd" name="passwd" value="'.$valuePasswd.'" 
      placeholder="Пароль" required', 'passwd');
    $t->form .= ServiceHTMLCode::addFormButton('type="submit" class="btn btn-primary" value="Войти"');
		if (isset($_SESSION['username'])) {
      $t->username = $_SESSION['username'];
    }
		include_once TEMPLATES . 'login/index.php';
	}

	private function actLogin()
  {
    $db = new ModelLogin();
    $row = $db->getData($_POST['username']);
    $_SESSION['user'] = $row['shortName'];
		$_SESSION['access'] = $row['access'];
    $_SESSION['name'] = $row['identificate'];
		if ($row['access'] === 'admin') {
      $url = $_SESSION['url'] ?? '/admin/';
      redirect($url);
			return;
		}
    $url = $_SESSION['url'] ?? '/theory/?page=1';
    redirect($url);
	}

	public function actVKlogin()
  {
    $code = $_GET['code'];
    $this->isData($code, 'Не возможно получить код! Сообщите о проблеме администратору');
    $token = file_get_contents('https://oauth.vk.com/access_token?client_id=' . VKclient_id . '&client_secret=' . VKclient_secret . '&redirect_uri=http://192.168.0.102/loginvk/&code=' . $code . '');
    $this->isData($token, 'Не возможно получить токен! Сообщите о проблеме администратору');
    $data = json_decode($token, true);
    $data_vk = file_get_contents('https://api.vk.com/method/users.get?v=5.92&access_token=' . $data['access_token'] . ''); //получение данных об пользователе
    $this->isData($data_vk, 'Не возможно получить данные о пользователе! Сообщите о проблеме администратору');
    $data_vk = json_decode($data_vk, true);
    $id_vk = $data_vk['response']['0']['id'];
    $db = new ModelLogin();
    $row = $db->getDataVk($id_vk);
    if (!empty($row)) {
      $_SESSION['username'] = $row['user'];
      $_SESSION['access'] = $row['access'];
      if ($row['access'] === 'admin') {
        $url = $_SESSION['url'] ?? '/admin/';
        redirect($url);
        return;
      }
      $url = $_SESSION['url'] ?? '/theory/?page=1';
      redirect($url);
    }
  }

  /**
   * @param string $data
   * @param string $message
   */
	private function isData(string $data, string $message)
  {
    $t = Template::getInstance();
    if (isset($data)){
      $t->errors = $message;
      include_once TEMPLATES . 'ViewError.php';
      return;
    }
  }

  private function actOut()
  {
    unset($_SESSION['user']);
    unset($_SESSION['access']);
    unset($_SESSION['name']);
    redirect('/login/');
  }
}