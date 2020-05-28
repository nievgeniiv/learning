<?php
Class ModelAdmHelp{

  /**
   * @param $student
   * @return array
   */
/*  public function getData(string $table, string $column, string $student) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $column . '=?';
    return $row = $db->getRows($sql, 's', $student);
  }*/

  public function getKol(string $student) : array
  {
    $db = DB::getInstance();
    if ($student !== 'all') {
      $sql = 'SELECT COUNT(*) FROM help WHERE whose=?';
      return $db->getRow($sql,'s', $student);
    }
    $sql = 'SELECT COUNT(*) FROM help';
    return $db->getRow($sql,'');
  }

  public function getData(int $page, string $student = 'all')
  {
    $db = DB::getInstance();
    if ($student === 'all') {
      $sql = 'SELECT * FROM help LIMIT ?, ?';
      return $row = $db->getRows($sql, 'ii',$page, 6);
    }
    $sql = 'SELECT * FROM help WHERE whose=? LIMIT ?, ?';
    return $row = $db->getRows($sql, 'sii',$student, $page, 6);
  }

  public function getFilterDataByAnswer(int $page, string $filter, string $student = 'all')
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM help';
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
    $sql = 'SELECT * FROM help';
    if ($student !== 'all') {
      if ($filter === 'dateChangeNew') {
        $sql .= ' WHERE whose=? ORDER BY dateChange DESC';
      } elseif ($filter === 'dateChangeOld') {
        $sql .= ' WHERE whose=? ORDER BY dateChange ASC';
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
      $sql .= $sql1;
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
        return $db->getRows($sql, $type, $student, '', $art, 6) ?? $this->getEmpty();
      }
      return $db->getRows($sql, $type, $student, $art, 6) ?? $this->getEmpty();
    }
    if ($filter === 'noHaveAnswer' or $filter === 'haveAnswer') {
      return $db->getRows($sql, $type, '', $art, 6) ?? $this->getEmpty();
    }
    return $db->getRows($sql, $type, $art, 6) ?? $this->getEmpty();
  }

  public function getOne(int $id) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM help WHERE id=?';
    return $row = $db->getRow($sql, 's', $id);
  }

  public function saveOne(string $table, string $column, int $id, string $data = null) : bool
  {
    $db = DB::getInstance();
    $sql = 'UPDATE ' . $table . ' SET ' . $column . '=? WHERE id=?';
    if ($data === null) {
      $data = $_POST['answer'];
    }
    return $db->setData($sql, 'si', $data, $id);
  }

  public function deleteOne(int $id) : bool
  {
    $db = DB::getInstance();
    $sql = 'delete from help where id=?';
    return $db->setData($sql,'s', $id);
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

  public function getEmpty() : array
  {
    return ['theme' => '',
      'id' => '',
      'whose' => '',
      'student' => '',
      'rating' => '',
      'date' => ''];
  }
}