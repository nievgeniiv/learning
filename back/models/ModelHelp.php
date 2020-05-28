<?php

Class ModelHelp{

  public function getKol(string $student) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT COUNT(*) FROM help WHERE whose=?';
    return $db->getRow($sql,'s', $student);
  }

  public function getFilterData(string $student, string $filter, int $art) : array
  {
    if ($student === 'all') {
      $sql1 = 'SELECT * FROM help';
      $type = '';
    } else {
      $sql1 = 'SELECT * FROM help WHERE whose=?';
      $type = 's';
    }
    $sql = '';
    $db = DB::getInstance();
    switch ($filter) {
      case 'haveRating':
        $sql .= ' AND rating IS NOT null';
        break;
      case 'noHaveRating':
        $sql .= ' AND rating IS null';
        break;
      case 'noHaveAnswer':
        $sql .= ' AND answer=?';
        $type .= 's';
        break;
      case 'haveAnswer':
        $sql .= ' AND answer<>?';
        $type .= 's';
        break;
      /*case 'dateChangeNew':
        $sql .= ' ORDER BY dateChange DESC';
        break;
      case 'dateChangeOld':
        $sql .= ' ORDER BY dateChange ASC';
        break;*/
    }
    if (strpos($sql1, 'WHERE') === false and $sql !== '') {
      $sql = $sql1 . ' WHERE' . substr($sql, 4);
    } elseif (strpos($sql1, 'WHERE') === false and $sql === '') {
      $sql = $sql1;
    } else {
      $sql = $sql1 . $sql;
    }
    if ($filter === 'dateChangeNew') {
      $sql .= ' ORDER BY dateChange DESC';
    }
    if ($filter === 'dateChangeOld') {
      $sql .= ' ORDER BY dateChange ASC';
    }
    $sql .= ' LIMIT ?, ?';
    $type .= 'ii';
    if ($student !== 'all') {
      if ($filter === 'noHaveAnswer' or $filter === 'haveAnswer') {
        return $db->getRows($sql, $type, $student, '', $art, 6) ?? [];
      }
      return $db->getRows($sql, $type, $student, $art, 6) ?? [];
    }
    if ($filter === 'noHaveAnswer' or $filter === 'haveAnswer') {
      return $db->getRows($sql, $type, '', $art, 6) ?? [];
    }
    return $db->getRows($sql, $type, $art, 6) ?? [];
  }

  public function saveData(string $message, string $student, int $id) : bool
  {
    $db = DB::getInstance();
    $date = date('Y-m-d H:i:s');
    if ($id === 0) {
      $sql = 'SELECT MAX(id) FROM help';
      $id = $db->getCell($sql, '')['MAX(id)'] + 1;
      $sql = 'INSERT INTO help() VALUE (?,?,?,?,?,?)';
      return $db->setData($sql, 'isssss', $id, $message, '', $student, $_SESSION['user'], $date);
    }
    $sql = 'UPDATE help SET message=?, dateChange=? WHERE id=?';
    return $db->setData($sql, 'ssi', $message, $date, $id);
  }

  public function getOne(int $id) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM help WHERE id=?';
    return $row = $db->getRow($sql, 's', $id);
  }

  /**
   * @param array $data
   * @return array
   */
  public function searchData(array $data) : array
  {
    $db = DB::getInstance();
    $row = [];
    foreach ($data as $key=>$value) {
      $sql = 'SELECT * FROM help WHERE message LIKE ?';
      $value = '%' . $value . '%';
      $row = array_merge($row, $db->getRows($sql, 's', $value));
    }
    return isset($row) ? $row : [ ];
  }
}