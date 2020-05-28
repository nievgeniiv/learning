<?php

Class ModelTheory{

  public function getData(int $art = 0) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM theory LIMIT ?, ?';
    return $row = $db->getRows($sql,'ii',$art, 6);
  }

  public function getKol() : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT COUNT(*) FROM theory';
    return $db->getRow($sql,'');
  }

  public function getOne(string $id) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM theory WHERE id=?';
    return $db->getRow($sql, 'i', $id);
  }

  public function searchData(array $data) : array
  {
    $db = DB::getInstance();
    $row = [];
    foreach ($data as $key=>$value) {
      $sql = 'SELECT * FROM theory WHERE theme LIKE ?';
      $value = '%' . $value . '%';
      $row = array_merge($row, $db->getRows($sql, 's', $value));
    }
    return isset($row) ? $row : [ ];
  }
}