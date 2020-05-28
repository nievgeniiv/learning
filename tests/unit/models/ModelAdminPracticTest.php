<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ModelAdminPracticTest extends Unit
{
  /**
   * @var \UnitTester
   */
  protected $tester;
  private $db;
  private $nof;
  private $id;
  private $dataDB;

  protected function _before()
  {
    $this->nof = getNof('practic') + 1;
    $this->id = getMaxId('practic') + 1;
    $this->dataDB = [
      'id' => $this->id,
      'theme' => 'Тестовая тема',
      'example' => 'Тестовый пример',
      'task' => 'Тестовое задание',
      'answer' => 'Тестовый ответ',
      'comments' => 'Тестовый коммент',
      'date' => '2019-05-29',
      'whose' => 'example',
      'student' => 'example',
      'rating' => 4,
    ];
    $this->tester->haveInDatabase('practic', $this->dataDB);
    $this->db = new ModelAdmPractic();
  }

  protected function _after()
  {
    $this->db = NULL;
    $this->id = NULL;
    $this->nof = NULL;
    $this->dataDB = NULL;
  }

  // tests
  public function testSomeFeature()
  {
    $this->tester->seeInDatabase('practic', $this->dataDB);
    $this->testGetKol();
    $this->testSearchData();
    $this->testSaveOne();
    $this->testGetOne();
    $this->testUpdateOne();
    //$this->testGetData();
    $this->testDeleteOne();
  }

  private function testGetKol()
  {
    $this->assertEquals(1, $this->db->getKol('example')['COUNT(*)']);
    $this->assertEquals($this->nof, $this->db->getKol('all')['COUNT(*)']);
  }

  private function testGetData()
  {
    foreach ($this->dataDB as $key => $value) {
      testArray($this->tester, $this->db->getData($key.'=', $value, 's'), $this->dataDB);
    }
  }

  private function testGetFilterData()
  {
  }

  private function testGetOne()
  {
    $this->dataDB['id'] += 1;
    $this->tester->seeInDatabase('practic', $this->dataDB);
    $data = $this->db->getOne($this->dataDB['id'], $this->dataDB['whose']);
    testArray($this->tester, $data, $this->dataDB);
  }

  private function testSaveOne()
  {
    $this->dataDB['answer'] = '';
    $this->dataDB['comments'] = '';
    $this->dataDB['rating'] = null;
    $this->db->saveOne($this->dataDB, $this->dataDB['whose'], $this->dataDB['student']);
  }

  private function testUpdateOne()
  {
    $this->dataDB['theme'] = 'Измененная тестовая тема';
    $this->dataDB['example'] = 'Измененный тестовый пример';
    $this->dataDB['task'] = 'Измененное тестовое задание';
    $this->dataDB['date'] = '2019-12-29';
    $t = Template::getInstance();
    $t->data = $this->dataDB;
    $this->db->updateOne($this->dataDB['id']);
    $this->tester->seeInDatabase('practic', $this->dataDB);
    $this->dataDB['comments'] = 'Новый тестовый комментарий';
    $this->dataDB['rating'] = 4;
    $t->data = $this->dataDB;
    $this->db->updateOne($this->dataDB['id'], true);
    $this->tester->seeInDatabase('practic', $this->dataDB);
  }

  private function testDeleteOne()
  {
    $this->db->deleteOne($this->dataDB['id']);
    $this->tester->dontSeeInDatabase('practic', $this->dataDB);
  }

  private function testSearchData()
  {
    $data = $this->db->searchData(['Тестовая тема']);
    testArray($this->tester, $data, $this->dataDB);
  }
}