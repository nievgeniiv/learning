<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelTheoryTest extends Unit
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
    $id = (int)getMaxId('theory') + 1;
    $this->dataDB = [
      'id' => $id,
      'theme' => 'Тестовая тема',
      'text' => 'Текстовый текст',
      'link' => 'Тестовая ссылка'
    ];
    $this->tester->haveInDatabase('theory', $this->dataDB);
    $this->db = new ModelTheory();
    $this->nof = (int)getNof('theory');
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
    $this->tester->seeInDatabase('theory', $this->dataDB);
    $this->testGetOne();
    $this->testSearchData();
    $this->testGetData();
    $this->testGetKol();
  }

  private function testArray(array $data)
  {
    foreach ($data as $key => $value) {
      if ($key != 'id') {
        $this->assertEquals($data[$key], $this->dataDB[$key]);
      }
    }
  }

  private function testGetOne()
  {
    $data = $this->db->getOne($this->nof);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testSearchData()
  {
    $data = $this->db->searchData(['Тестовая тема']);
    $this->testArray($data);
  }

  private function testGetData()
  {
    $pages = $this->nof / 6;
    if ($this->nof - $pages > 0) {
      $pages++;
    }
    $data = $this->db->getData($pages);
    $kol = count($data) - 1;
    testArray($this->tester, $data[$kol], $this->dataDB);
  }

  private function testGetKol()
  {
    $this->tester->assertEquals($this->nof, $this->db->getKol()['COUNT(*)']);
  }
}