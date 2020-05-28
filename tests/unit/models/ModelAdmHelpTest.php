<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelAdmHelpTest extends Unit
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
      $this->nof = getMaxId('help') + 1;
      $this->dataDB = [
        'id' => $this->nof,
        'message' => 'Тестовое сообщение',
        'answer' => 'Тестовый ответ',
        'whose' => 'example',
        'student' => 'example',
      ];
      $this->tester->haveInDatabase('help', $this->dataDB);
      $this->db = new ModelAdmHelp();
    }

    protected function _after()
    {
      $this->dataDB = NULL;
      $this->db = NULL;
      $this->nof = NULL;
    }

    // tests
    public function testSomeFeature()
    {
      $this->tester->seeInDatabase('help', $this->dataDB);
      $this->testGetOne();
      $this->testSearchData();
      $this->testGetKol();
      $this->testSaveOne();
      $this->testDeleteOne();
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
    $localNof = (int)$this->db->getKol('all')['COUNT(*)'];
    $this->tester->assertEquals($this->nof, $localNof);
  }

  private function testSaveOne()
  {
    $this->dataDB['answer'];
    $this->db->saveOne('help', 'answer', $this->nof, $this->dataDB['answer']);
    $this->tester->seeInDatabase('help', $this->dataDB);
  }

  private function testDeleteOne()
  {
    $this->db->deleteOne($this->nof);
    $this->tester->dontSeeInDatabase('help', $this->dataDB);
  }
}