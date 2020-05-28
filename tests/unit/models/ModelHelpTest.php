<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelHelpTest extends Unit
{
  /**
   * @var \UnitTester
   */
  protected $tester;
  private $db;
  private $dataDB;
  private $nof;

  protected function _before()
  {
    $this->nof = getMaxId('help') + 1;
    $this->dataDB = [
      'id' => $this->nof,
      'message' => 'Тестовое сообщение',
      'answer' => 'Тестовый ответ',
      'whose' => 'example',
      'student' => 'example',
    ];
    $this->tester->haveInDatabase('help', $this->dataDB);
    $_SESSION['user'] = 'example';
    $this->db = new ModelHelp();
  }

  protected function _after()
  {
    $this->dataDB = NULL;
    $this->db = NULL;
    $this->nof = NULL;
    unset($_SESSION['user']);
  }

  // tests
  public function testSomeFeature()
  {
    $this->tester->seeInDatabase('help', $this->dataDB);
    $this->testGetOne();
    $this->testSearchData();
    $this->testGetKol();
    $this->testSaveData();
  }

  private function testGetOne()
  {
    $data = $this->db->getOne($this->nof);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testSearchData()
  {
    $data = $this->db->searchData(['Текстовое сообщение']);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testGetKol()
  {
    $localNof = $this->db->getKol($this->dataDB['student'])['COUNT(*)'];
    $this->tester->assertEquals(1, $localNof);
  }

  private function testSaveData()
  {
    $this->dataDB['message'] = 'Новое текстовое сообщение';
    $this->db->saveData($this->dataDB['message'], $this->dataDB['student'], (int)$this->dataDB['id']);
    $this->tester->seeInDatabase('help', $this->dataDB);
    $this->dataDB['id'] += 1;
    $this->dataDB['answer'] = '';
    $this->db->saveData($this->dataDB['message'], $this->dataDB['student'], 0);
    $this->tester->seeInDatabase('help', $this->dataDB);
  }
}