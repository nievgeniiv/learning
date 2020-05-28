<?php


/**
 * @property array students
 * @property array practic_list
 * @property array practic
 * @property array parcticError
 * @property mixed page_title
 * @property mixed errors
 * @property string payNowButtonUrl
 * @property string receiverEmail
 * @property string itemName
 * @property float amount
 * @property string returnUrl
 * @property string currency
 * @property string ajax
 * @property array|false listFiles
 * @property string theme
 * @property array help
 * @property array theory
 * @property string text
 * @property string link
 * @property array dataPractic
 * @property mixed dataHelp
 * @property mixed user
 * @property string head
 * @property mixed breadcrumb
 * @property float pageKol
 * @property int nextPage
 * @property int previousPage
 * @property string searchData
 * @property string RichEdit
 * @property mixed navbar
 * @property mixed form
 * @property mixed style
 * @property mixed scriptJs
 * @property mixed pagination
 * @property string input
 * @property void button
 * @property mixed username
 * @property mixed formDeleteData
 * @property string formDeleteDataClose
 * @property void table
 * @property string button2
 * @property string button1
 * @property string dataList
 * @property void select
 * @property string message
 */
class Template {

	private static $instance;
	private $data;

  public static function getInstance() : Template {
//	public static function getInstance() {

		if (self::$instance === null) {
			self::$instance = new Template();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->data = [];
	}

	public function __get(string $name) {
		return $this->data[$name];
	}

	public function __set(string $name, $value) {
		$this->data[$name] = $value;
	}

	public function __isset(string $name) : bool  {
		return isset($this->data[$name]);
	}

	public function safe(string $name, $default) {
		if ($this->__isset($name)) {
			return $this->__get($name);
		}
		return $default;
	}

	public function formatError(string $message): string {
		return '<p class="error-message">' . $message . '</p>';
	}

}