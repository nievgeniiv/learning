<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelPracticTest extends Unit
{
  /**
   * @var \UnitTester
   */
  protected $tester;
  private $db;
  private $nof;
  private $dataDB;

  protected function _before()
  {
    $this->nof = getMaxId('practic') + 1;
    $this->dataDB = [
      'id' => $this->nof,
      'theme' => 'Тестовая тема',
      'example' => 'Текстовый пример',
      'task' => 'Тестовое задание',
      'answer' => 'Тестовый ответ',
      'comments' => 'Тестовый коммент',
      'date' => '2019-05-29',
      'whose' => 'example',
      'student' => 'example',
      'rating' => '4',
    ];
    $this->tester->haveInDatabase('practic', $this->dataDB);
    $this->db = new ModelPractic();
  }

  protected function _after()
  {
    $this->db = NULL;
    $this->nof = NULL;
    $this->dataDB = NULL;
  }

  // tests
  public function testSomeFeature()
  {
    $this->tester->seeInDatabase('practic', $this->dataDB);
    $this->assertEquals(1, $this->db->getKol('example')['COUNT(*)']);
    $this->testSearchData();
    $this->testGetFilterData();
    $this->testSaveData();
    $this->testGetOne();
  }

  private function testSearchData()
  {
    $data = $this->db->searchData(['Тестовая тема']);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testGetFilterData()
  {
  }

  private function testSaveData()
  {
    $this->dataDB['answer'] = 'Новый Тестовый ответ';
    $this->db->saveData($this->nof, $this->dataDB['answer']);
    $this->tester->seeInDatabase('practic', $this->dataDB);
  }

  private function testGetOne()
  {
    $data = $this->db->getOne($this->nof);
    testArray($this->tester, $data, $this->dataDB);
  }
}