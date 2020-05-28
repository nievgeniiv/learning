<?php

/** @noinspection PhpIncompatibleReturnTypeInspection */
Class DB {

	private static $instance;
  private $mysqli;
	private $link;

	public static function getInstance(): DB {
		if (self::$instance === null) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->link = null;
	}

	public function connect() {

		if ($this->link === null) {
			$this->link = @ mysqli_connect(HOST, USER, PASSWD, DATABASE);
			if (mysqli_connect_error()) {
			  $str = 'Ошибка соединения с MySQL. Параметры: HOST=' . HOST .
                ', USER=' . USER . ', PASSWD=' . PASSWD . ', DATABASE=' . DATABASE;
          writeFile($str);
				exit('Ошибка соединения с MySQL');
			}
		}
    //mysqli_query($this->link, "SET NAMES 'utf8'");
    //mysqli_query($this->link, "SET CHARACTER SET 'utf8'");
		return $this->link;
	}

	private function __clone() { }

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return string
   */
  private function prepare(string $sql, string $type, array $param) : string
  {
    $this->mysqli = $this->connect();
    $i = 0;
    foreach ($param as $value) {
      if ($type[$i] === 's') {
        $value = '"' . $this->mysqli ->real_escape_string($value) . '"';
      } else {
        $value = $this->mysqli ->real_escape_string($value);
      }
      $pos = strpos($sql, '?');
      $sql = substr_replace($sql, $value, $pos, strlen('?'));
      $i++;
    }
    return $sql;
  }

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return array|false
   */
	public function getRows(string $sql, string $type, ...$param): array
  {
    $nof = substr_count($sql ,'?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $type, $param);
      $res = mysqli_query($this->mysqli, $sql);
      if ($res === false) {
        $this->writeLog('', [], true);
        return [];
      }
      while ($k = mysqli_fetch_assoc($res)) {
        $row[] = $k;
      }
      /** @noinspection PhpUndefinedVariableInspection */
      return $row ?? [];
    } else {
      $this->writeLog($sql, $param);
      return [];
    }
	}

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return array
   */
	public function getRow(string $sql, string $type, ...$param): array
  {
    $nof = substr_count($sql, '?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $type, $param);
      $res = mysqli_query($this->mysqli, $sql);
      if ($res === false) {
        $this->writeLog($sql, $param, true);
        return [];
      }
      $row = mysqli_fetch_assoc($res);
      if ($row === null) {
        return [];
      }
      return $row;
    } else {
      $this->writeLog($sql, $param);
      return [];
    }
	}

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return array|null
   */
	public function getCell(string $sql, string $type, ...$param)
  {
    $nof = substr_count($sql, '?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $type, $param);
      $res = mysqli_query($this->mysqli, $sql);
      if ($res === false) {
        $this->writeLog($sql, $param, true);
        return false;
      }
      $value = mysqli_fetch_assoc($res);
      return $value;
    } else {
      $this->writeLog($sql, $param);
      return false;
    }
	}

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return array
   */
	public function getColumn(string $sql, string $type, ...$param): array
  {
    $nof = substr_count($sql, '?');
    if (count($param) === $nof) {
      $sql = $this->prepare($sql, $type, $param);
      $res = mysqli_query($this->mysqli , $sql);
      if ($res === false) {
      $this->writeLog('', [], true);
      return [];
    }
      while ($k = mysqli_fetch_row($res)) {
        $row[] = $k['0'];
      }
      /** @noinspection PhpUndefinedVariableInspection */
      return $row;
    } else {
      $this->writeLog($sql, $param);
      return false;
    }
	}

  /**
   * @param string $sql
   * @param string $type
   * @param array $param
   * @return bool
   */
	public function setData(string $sql, string $type, ...$param) : bool
  {
	  $nof = substr_count($sql, '?');
	  if (count($param) === $nof) {
      $sql = $this->prepare($sql, $type, $param);
      return $res = mysqli_query($this->mysqli, $sql);
    } else {
      $this->writeLog($sql, $param);
	    return false;
    }
	}

  /**
   * @param string $sql
   * @param array $param
   * @param bool $ok
   */
	private function writeLog(string $sql, array $param, bool $ok = false)
  {
    if ($ok === true) {
      $str = 'Запращиваемые данные не существуют.';
    } else {
      $str = 'Количество данных не соответсвует количеству входных параметров.';
    }
    $str .= ' Запрос: ' . $sql . '  Параметры:';
    foreach ($param as $item) {
      $str .= $item .', ';
    }
    writeFile($str);
  }
}