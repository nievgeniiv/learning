<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelAdminTheoryTest extends Unit
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
    $this->db = new ModelAdmTheory();
    $this->dataDB = [
      'theme' => 'Тестовая тема',
      'text' => 'Текстовый текст',
      'link' => 'Тестовая ссылка'
    ];
    $this->db->saveData($this->dataDB['theme'], $this->dataDB['text'], $this->dataDB['link']);
    $this->nof = $this->db->getKol();
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
    $this->testSearchData();
    $this->testGetDataAll();
    $this->testUpdateData();
    $this->testDeleteOne();
  }

  private function testSearchData()
  {
    $data = $this->db->searchData(['Тестовая тема']);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testGetDataAll()
  {
    $pages = $this->nof % 6;
    if ($this->nof['COUNT(*)'] - $pages > 0) {
      $pages++;
    }
    $data = $this->db->getData($pages);
    $kol = count($data) - 1;
    testArray($this->tester, $data[$kol], $this->dataDB);
  }

  private function testUpdateData()
  {
    $this->dataDB = [
      'theme' => 'Измененная тестовая тема',
      'text' => 'Измененный текстовый текст',
      'link' => 'Измененная тестовая ссылка'
    ];
    $this->db->updateData($this->nof['COUNT(*)'], $this->dataDB['theme'], $this->dataDB['text'], $this->dataDB['link']);
    $this->tester->seeInDatabase('theory', $this->dataDB);
  }

  private function testDeleteOne()
  {
    $this->db->deleteOne($this->nof['COUNT(*)']);
    $this->tester->dontSeeInDatabase('theory', $this->dataDB);
  }
}