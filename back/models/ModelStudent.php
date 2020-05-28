<?php
/**
 * Created by PhpStorm.
 * User: zheny
 * Date: 24.02.2019
 * Time: 17:48
 */

class ModelStudent
{

  public function getKol() : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT COUNT(*) FROM users';
    return $db->getRow($sql,'');
  }

  public function getData(int $art = 0) : array {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM users WHERE access=? LIMIT ?, ?';
    return $db->getRows($sql, 'sii', 'student', $art, 6);
  }

  public function getOne(string $identificate) : array {
    $db = DB::getInstance();
    $sql = 'SELECT shortName FROM users WHERE identificate=?';
    return $db->getRows($sql, 's', $identificate);
  }

  public function getDataStudent(string $identificate) : array {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM users WHERE identificate=?';
    return $db->getRows($sql, 's', $identificate);
  }

  public function searchData(array $data) : array
  {
    $db = DB::getInstance();
    $row = [];
    foreach ($data as $key=>$value) {
      $sql = 'SELECT * FROM users WHERE name LIKE ?';
      $value = '%' . $value . '%';
      $row = array_merge($row, $db->getRows($sql, 's', $value));
    }
    return isset($row) ? $row : [ ];
  }
}