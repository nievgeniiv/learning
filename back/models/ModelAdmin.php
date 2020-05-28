<?php

Class
ModelAdmin
{

  public function saveData($login, $username, $passwd, $email, $shortName) : bool
  {
    $db = DB::getInstance();
    $passwd = md5($passwd);
    $identificate = md5($username);
    $sql = 'SELECT MAX(id) AS id FROM users';
    $max_id = $db->getColumn($sql, '');
    $id = $max_id[0] + 1;
    $sql = 'INSERT INTO users() VALUES(?, ?, ?, ?, ?, ?, "student", 0, ?)';
    return $db->setData($sql, 'issssss', $id, $login, $passwd, $username, $email, $identificate, $shortName);
  }

  public function updateData(string $col, string $data, string $identificate, bool $isName = false) : bool
  {
    $db = DB::getInstance();
    if ($col === 'passwd') {
      $data = md5($data);
    }
    if ($isName === false) {
      $sql = 'UPDATE users SET ' . $col . '=? WHERE identificate=?';
      return $db->setData($sql, 'ss', $data, $identificate);
    }
    $sql = 'UPDATE users SET ' . $col . '=?, identificate=? WHERE identificate=?';
    return $db->setData($sql, 'sss', $data, md5($data), $identificate);
  }

  public function deleteOne(string $identificate) : bool
  {
    $db = DB::getInstance();
    $sql = 'delete from users where identificate=?';
    return $db->setData($sql,'s', $identificate);
  }
}