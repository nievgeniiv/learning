<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelLoginTest extends Unit
{
  /**
   * @var \UnitTester
   *
   */
  protected $tester;
  private $db;
  private $dataDB;

  protected function _before()
  {
    $this->db = new ModelLogin();
    $this->dataDB = [
      'id' => getMaxId('users') + 1,
      'user' => 'Ivanov',
      'passwd' => md5('ivanov'),
      'name' => 'Иванов Иван Иванович',
      'email' => 'ivanov@mail.ru',
      'identificate' => md5('Иванов Иван Иванович'),
      'access' => 'admin',
      'id_vk' => 222222,
      'shortName' => 'Иванов И.И.'
    ];
    $this->tester->haveInDatabase('users', $this->dataDB);
  }

  protected function _after()
  {
    $this->db = NULL;
    $this->dataDB = NULL;
  }

  public function testSomeFeature()
  {
    $this->tester->seeInDatabase('users', $this->dataDB);
    $this->testGetData();
    $this->testSetUser();
  }

  private function testArray(array $data)
  {
    foreach ($data as $key => $value) {
      if ($key != 'id') {
        if ($key === 'passwd' or $key === 'identificate') {
          $this->assertEquals($data[$key], $this->dataDB[$key]);
        } else {
          $this->assertEquals($data[$key], $this->dataDB[$key]);
        }
      }
    }
  }

  private function testGetData()
  {
    $data = $this->db->getData($this->dataDB['user']);
    $this->testArray($data);
  }

  private function testSetUser()
  {
    $data = $this->dataDB;
    $data['user'] = 'IvvanovNew';
    $data['passwd'] = 'ivanov';
    $data['identificate'] = 'Иванов Иван Иванович';
    $this->db->setUser($data);
    $this->tester->seeInDatabase('users', $this->dataDB);
  }
}