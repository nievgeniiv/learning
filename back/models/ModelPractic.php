<?php

Class ModelPractic {

  public function getKol(string $student) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT COUNT(*) FROM practic WHERE whose=?';
    return $db->getRow($sql,'s', $student);
  }

  public function searchData(array $data) : array
  {
    $db = DB::getInstance();
    $row = [];
    foreach ($data as $key=>$value) {
      $sql = 'SELECT * FROM practic WHERE theme LIKE ?';
      $value = '%' . $value . '%';
      $row = array_merge($row, $db->getRows($sql, 's', $value));
    }
    return isset($row) ? $row : [ ];
  }

  public function getData(int $page, string $student = 'all')
  {
    $db = DB::getInstance();
    if ($student === 'all') {
      $sql = 'SELECT * FROM practic LIMIT ?, ?';
      return $row = $db->getRows($sql, 'ii',$page, 6);
    }
    $sql = 'SELECT * FROM practic WHERE whose=? LIMIT ?, ?';
    return $row = $db->getRows($sql, 'sii',$student, $page, 6);
  }

  public function getFilterDataByRating(int $page, string $filter, string $student = 'all')
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM practic';
    if ($student !== 'all') {
      if ($filter === 'haveRating') {
        $sql .= ' WHERE whose=? AND rating IS NOT null';
      } elseif ($filter === 'noHaveRating') {
        $sql .= ' WHERE whose=? AND rating IS null';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'sii', $student, $page, 6);
    } else {
      if ($filter === 'haveRating') {
        $sql .= ' WHERE rating IS NOT null';
      } elseif ($filter === 'noHaveRating') {
        $sql .= ' WHERE rating IS null';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'ii', $page, 6);
    }
  }

  public function getFilterDataByAnswer(int $page, string $filter, string $student = 'all')
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM practic';
    if ($student !== 'all') {
      if ($filter === 'haveAnswer') {
        $sql .= ' WHERE whose=? AND answer<>""';
      } elseif ($filter === 'noHaveAnswer') {
        $sql .= ' WHERE whose=? AND answer=""';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'sii', $student, $page, 6);
    } else {
      if ($filter === 'haveAnswer') {
        $sql .= ' WHERE answer<>""';
      } elseif ($filter === 'noHaveAnswer') {
        $sql .= ' WHERE answer=""';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'ii', $page, 6);
    }
  }

  public function getFilterDataByOld(int $page, string $filter, string $student = 'all')
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM practic';
    if ($student !== 'all') {
      if ($filter === 'dateChangeNew') {
        $sql .= ' WHERE whose ? ORDER BY dateChange DESC';
      } elseif ($filter === 'dateChangeOld') {
        $sql .= ' WHERE whose ? ORDER BY dateChange ASC';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'sii', $student, $page, 6);
    } else {
      if ($filter === 'dateChangeNew') {
        $sql .= ' ORDER BY dateChange DESC';
      } elseif ($filter === 'dateChangeOld') {
        $sql .= ' ORDER BY dateChange ASC';
      }
      $sql .= ' LIMIT ?, ?';
      return $db->getRows($sql, 'ii', $page, 6);
    }
  }

  public function getFilterData(
    string $student,
    string $rating,
    string $answer,
    string $sort,
    int $art
  ) : array
  {
    $sql = 'SELECT * FROM practic WHERE whose=?';
    $type = 's';
    $db = DB::getInstance();
    switch ($rating) {
      case 'true':
        $sql .= ' AND rating IS NOT null';
        break;
      case 'false':
        $sql .= ' AND rating IS null';
        break;
    }
    switch ($answer) {
      case 'true':
        $sql .= ' AND answer<>?';
        $type .= 's';
        break;
      case 'false':
        $sql .= ' AND answer=?';
        $type .= 's';
        break;
    }
    if ($sort === 'new') {
      $sql .= ' ORDER BY dateChange DESC';
    }
    if ($sort === 'old') {
      $sql .= ' ORDER BY dateChange ASC';
    }
    $sql .= ' LIMIT ?, ?';
    $type .= 'ii';
    if ($answer === 'all') {
      return $db->getRows($sql, $type, $student, $art, 6);
    }
    return $db->getRows($sql, $type, $student, '', $art, 6);
  }

  public function saveData(int $id, string $answer) : bool
  {
    $db = DB::getInstance();
    $date = date('Y-m-d H:i:s');
    $sql = 'UPDATE practic SET answer=?, dateChange=? WHERE id=?';
    return $db->setData($sql, 'ssi', $answer, $date, $id);

  }

  public function getOne(string $id) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM practic WHERE id=?';
    return $db->getRow($sql, 'i', $id);
  }
}