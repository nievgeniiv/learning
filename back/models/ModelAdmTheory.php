<?php

Class ModelAdmTheory{

  public function getData(int $art = 0) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM theory LIMIT ?, ?';
    return $row = $db->getRows($sql, 'ii',$art, 6);
  }

  public function getKol() : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT COUNT(*) FROM theory';
    return $db->getRow($sql,'');
  }

  public function saveData(string $theme, string $text, string $link) : bool
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(Id) as id FROM theory';
    $row = $db->getColumn($sql, '');
    $id = $row['0'] + 1;
    $sql = 'INSERT INTO theory() VALUES(?, ?, ?, ?)';
    return $db->setData($sql, 'ssss', $id, $theme, $text, $link);
  }

  public function updateData(int $id, string $theme, string $text, string $link) : bool
  {
    $db = DB::getInstance();
    $sql = 'UPDATE theory SET theme=?, text=?, link=? WHERE id=?';
    return $db->setData($sql, 'ssss', $theme, $text, $link, $id);
  }

  public function deleteOne(int $id) : bool
  {
    $db = DB::getInstance();
    $sql = 'delete from theory where id=?';
    return $db->setData($sql,'s', $id);
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

  public function getNof()
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(Id) as id FROM theory';
    $row = $db->getColumn($sql, '');
    return $row['0'];
  }
}