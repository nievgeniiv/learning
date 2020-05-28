<?php

/**
 * @param string $url
 * @param bool $is_exit
 */
function redirect(string $url, bool $is_exit = true) {

	if (PHP_SAPI === 'cgi') {
		header('Status: 301 Moved Permanently');
	} else {
		header('HTTP/1.0 301 Moved Permanently');
	}
	header('Location: ' . $url);

	if ($is_exit) {
		exit();
	}
}

/**
 * @param string $name
 * @param string $msg
 */
function errorView(string $name) : string
{
  $t = Template::getInstance();
  if (isset($t->parcticError[$name])) {
    return '<p class="text-danger">'.$t->parcticError[$name].'</p>';
  }
  return '';
}

function Selected(string $data, string $value) : string
{
  if ($_GET[$data] === $value) {
    return 'selected';
  }
  return '';
}

function isPost(){
  $isPost = null;
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isPost = $_SERVER['REQUEST_METHOD'];
  }
  return $isPost;
}

function isGet() {
  static $isGet = null;

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $isGet = $_SERVER['REQUEST_METHOD'];
  }
  return $isGet;
}

function writeFile($str)
{
  $fd = fopen(LOG . "log.txt", 'a') or die("не удалось создать файл");
  $data = date('d.M.Y H:i:s') . ' ' . $str . "\n";
  fwrite($fd, $data);
  fclose($fd);
}