<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

Class DBobject {

  private static $instance;

  private static $link;

  public static function getInstance(): DBobject {
    if (self::$instance === null) {
      self::$instance = new DBobject();
    }
    return self::$instance;
  }

  private function __construct() {
    self::$link = null;
  }

  public static function connect() {

    if (self::$link === null) {
      self::$link = new mysqli(HOST, USER, PASSWD, DATABASE);
      if (mysqli_connect_error()) {
        exit('Ошибка соединения с MySQL');
      }
      mysqli_query(self::$link, "SET NAMES 'utf8'");
      mysqli_query(self::$link, "SET CHARACTER SET 'utf8'");
    }

    return self::$link;
  }

  private function __clone() { }

  public static function get(string $query, string $type = '', $param = array()) : array
  {
    $mysql = self::connect();
    $mysql =$mysql->stmt_init();
    $mysql->prepare($query);
    if ($type !== ''){
      $mysql->bind_param($type, ...$param);
    }
    $mysql->execute();
    $res = $mysql->get_result();
    $row = $res->fetch_all();
    if ($row === []) {
      $log = new ModelLog();
      $log->writeLog();
    }
    return $row;
  }

  public static function set(string $query,string $type, $param = array()) : bool
  {
    $mysql = self::connect();
    $mysql =$mysql->stmt_init();
    $mysql->prepare($query);
    $mysql->bind_param($type, ...$param);
    $mysql->execute();
    return true;
  }
}