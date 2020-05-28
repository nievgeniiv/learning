<?php

/* TODO: Реорганизуем Route

Route::add('/login/', 'Login');

Route::add('/admin/', 'Adm');
Route::add('/admin/theory/', 'AdmTheory');
Route::add('/admin/theory/add/', 'AdmTheoryAdd');

*/

class Route {

  private static $instance;

	/** @var string $uri Оригинальный REQUEST_URI */
	public $uri;
	/** @var array $url Разобранная ссылка */
	public $url;

	/** @var int $nof Количество сегментов */
	public $nof;

  public static function getInstance(): Route {
    if (self::$instance === null) {
      self::$instance = new Route();
    }
    return self::$instance;
  }

  private function __clone() { }

	public function __construct() {
		$this->uri = $_SERVER['REQUEST_URI'] ?? '/';
		$this->url = trim($this->uri, '/ ');

		if (empty($this->url)) {
			$this->url = [];
		} else {
			$this->url = explode('/', $this->url);
		}
		$this->nof = count($this->url);
	}

  public function go() : Controller
  {

  	if (empty($this->url)) {
      return new ControllerHome();
    }
    switch ($this->url[0]) {
      case 'login':
      	if ($this->nof === 1) {
		      return new ControllerLogin($this->url);
	      }
        return new ControllerLogin($this->url);
	      break;
      case 'admin':
	      switch ($this->url[1]) {
		      case 'theory':
            return new ControllerAdmTheory($this->url);
			      break;
			    case 'practic':
		      	if ($this->nof <= 5) {
				      return new ControllerAdmPractic($this->url);
			      }
			      break;
		      case 'help':
			      return new ControllerAdmHelp($this->url);
			      break;
          case 'ajax':
            return new ControllerAjax($this->url);
            break;
	      }
        return new ControllerAdm($this->url);
	      break;
      case 'captcha':
        return new ControllerCaptcha($this->url);
        break;
      case 'simple':
        return new ControllerSimple($this->url);
        break;
      case 'theory':
        return new ControllerTheory($this->url);
      case 'practic':
        return new ControllerPractic($this->url);
      case 'help':
        return new ControllerHelp($this->url);
      case 'pay':
        return new ControllerPay($this->url);
        break;
    }

    return new Controller404($this->url);
  }

}

