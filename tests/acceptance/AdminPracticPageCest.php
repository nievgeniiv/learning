<?php

require_once FUNCTIONS;

class AdminPracticPageCest
{
  private $dataDB;
  private $newPractic;

  public function _before(AcceptanceTester $I)
  {
    testLogin($I, testLoginAdmin, testPasswdAdmin);
    $I->click('Практика');
    //$this->dataDB = getDataPage('ModelAdmPractic', 1);
    $db = new ModelAdmPractic;
    $this->dataDB = $db->getFilterData('all', 'all', 1);
    $I->wantTo('Test Practic page for Admin');
    $this->newPractic = [
      'id' => getMaxId('Practic') + 1,
      'theme' => 'Тестовая тема практики',
      'example' => 'Текст тестового примера по практике',
      'task' => 'Тестовое задание по практике',
      'answer' => 'Тестовый ответ по практике',
      'comments' => 'Тестовый коммент по практике',
      'date' => 'Тестовый дата для ответа',
      'whose' => 'user',
      'student' => 'user',
      'rating' => '2',
      'search' => 'Тестовая',
    ];
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testPracticPage($I);
    $this->testAddNewPractic($I);

  }

  private function testPracticPage(AcceptanceTester $I)
  {
    testNavBar($I, true);
    $breadcrumb = ['Домашняя страница', 'Практика'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Практика');
    $I->see('Тема', 'thead');
    $I->see('Студент', 'thead');
    $I->see('Оценка', 'thead');
    $I->see('Задание просрочено', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      $I->see($this->dataDB[$i]['theme'], 'tbody');
      $I->see($this->dataDB[$i]['student'], 'tbody');
      $I->see($this->dataDB[$i]['rating'], 'tbody');
      $i++;
    }
  }

  private function testAddNewPractic(AcceptanceTester $I)
  {
    $I->click('Добавить новое задание');
    testNavBar($I, true);
    $breadcrumb = ['Домашняя страница', 'Практика', 'Редактирование практики'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Новое задание по практике');
    //$I->fillField('theme', );
  }
}
