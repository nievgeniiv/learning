<?php

abstract class Controller {

	protected $url;

	public function __construct(array $url)
  {
    $t = Template::getInstance();
    $t->style = ServiceHTMLCode::addStyle();
    $t->scriptJs = ServiceHTMLCode::addScriptJS();
		$this->url = $url;
	}

	abstract public function run();

  public function act404()
  {
		include_once TEMPLATES . 'View404.php';
	}

}

