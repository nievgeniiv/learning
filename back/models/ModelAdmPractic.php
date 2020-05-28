<?php

Class ModelAdmPractic{

  /**
   * @param string $student
   * @return array
   */
  public function getKol(string $student) : array
  {
    $db = DB::getInstance();
    if ($student !== 'all') {
      $sql = 'SELECT COUNT(*) FROM practic WHERE whose=?';
      return $db->getRow($sql,'s', $student);
    }
    $sql = 'SELECT COUNT(*) FROM practic';
    return $db->getRow($sql,'');
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

  public function getFilterData(string $student, string $filter, int $art) : array
  {
    if ($student === 'all') {
      $sql1 = 'SELECT * FROM practic';
      $type = '';
    } else {
      $sql1 = 'SELECT * FROM practic WHERE whose=?';
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

  /**
   * @param int $id
   * @return array
   */
  public function getOne(int $id, string $student) : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM practic WHERE whose=? AND id=?';
    return $db->getRows($sql, 'ss', $student, $id);
  }

  /**
   * @param array $data
   * @return bool
   */
  public function saveOne(array $data, string $student, string $shortName) : bool
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(Id) as id FROM practic';
    $row= $db->getColumn($sql, '');
    $id = $row['0'] + 1;
    $date = date('Y-m-d', strtotime($data['date']));
    $sql = 'INSERT INTO practic() VALUES(?, ?, ?, ?, "", "", ?, ?, ?, null, ?)';
    $db->setData($sql, 'isssssssss', $id, $data['theme'], $data['example'], $data['task'],
                    $date, $student, $shortName, date('Y-m-d H:i:s'));
    return true;
  }

  /**
   * @param int $id
   * @param bool $comments
   * @return bool
   */
  public function updateOne(int $id, bool $comments = false) : bool
  {
    $db = DB::getInstance();
    $t = Template::getInstance();
    $dateChange = date('Y-m-d H:i:s');
    if ($comments === false) {
      $sql = 'UPDATE practic SET theme=?, example=?, task=?, dateChange=?, date=? WHERE id=?';
      $db->setData($sql,'sssssi', $t->data['theme'], $t->data['example'], $t->data['task'], $dateChange,
                          $t->data['date'], $id);
      return true;
    } else {
      $rating = (int)$t->data['rating'];
      $sql = 'update practic set comments=?, dateChange=?, rating=? where id=?';
      $db->setData($sql, 'ssii', $t->data['comments'], $dateChange, $rating, $id);
      return true;
    }
  }

  public function deleteOne(int $id) : bool
  {
    $db = DB::getInstance();
    $sql = 'delete from practic where id=?';
    return $db->setData($sql,'i', $id);
  }

  /**
   * @param string $theme
   * @param int|null $excludeId
   * @return bool
   */
  public function isExistByTheme(string $theme, int $excludeId = null) : bool
  {
    $db = DB::getInstance();
    $theme = trim($theme);
    $sql = 'SELECT * FROM practic WHERE theme=?';
    if ($excludeId === 0 && $db->getCell($sql,'s', $theme)['theme'] === $theme){
      return false;
    }
    if ($excludeId !== (int)$db->getCell($sql, 's', $theme)['id'] &&
          $db->getCell($sql, 's', $theme)['theme'] === $theme){
      return false;
    }
    return true;
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

  /**
   * @param array $data
   * @return array
   */
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
}