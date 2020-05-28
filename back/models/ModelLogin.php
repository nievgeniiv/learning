<?php

Class ModelLogin {

  /**
   * @param $userName
   * @return array
   */
  public function getData($userName): array {
		$db = DB::getInstance();
    $sql = 'SELECT * FROM users WHERE user=?';
		return $db->getRow($sql, 's', $userName);
	}

  /**
   * @param $id
   * @return array
   */
  public function getDataVk($id): array {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM users WHERE id_vk=?';
    return $db->getRow($sql, 's', $id);
  }

  public function getAllData() : array
  {
    $db = DB::getInstance();
    $sql = 'SELECT * FROM users';
    return $db->getRows($sql, '');
  }

  public function setUser(array $data)
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(Id) as id FROM users';
    $row = $db->getColumn($sql, '');
    $id = $row['0'] + 1;
    $sql = 'INSERT INTO users() VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $db->setData($sql, 'sssssssss', $id, $data['user'], md5($data['passwd']), $data['name'], $data['email'],
      md5($data['identificate']), $data['access'], $data['id_vk'], $data['shortName']);
  }
}