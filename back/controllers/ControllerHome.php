<?php

class ControllerHome extends Controller
{

  public function __construct() { }

  public function run()
  {
		$t = Template::getInstance();
		$this->htmlRun($t);
		$t->page_title = 'Добро пожаловать на сайт по обучению веб-программированию';
		include TEMPLATES . 'home.php';
	}

  private function htmlRun(Template $t)
  {
    $t->style = ServiceHTMLCode::addStyle();
    $t->scriptJs = ServiceHTMLCode::addScriptJS();
    $t->navbar = ServiceHTMLCode::addNavbar();
  }
}