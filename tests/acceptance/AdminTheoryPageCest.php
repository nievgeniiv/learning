<?php

require_once FUNCTIONS;

class AdminTheoryPageCest
{

  private $dataDB;
  private $newTheory;
  private $nof;

  public function _before(AcceptanceTester $I)
  {
    testLogin($I, testLoginAdmin, testPasswdAdmin);
    $I->click('Теория');
    $this->dataDB = getDataPage('ModelAdmTheory', 1);
    $this->nof = getMaxId('theory') + 1;
    $I->wantTo('Test Theory page for Admin');
    $this->newTheory = [
      'theme' => 'Тестовая тема теории',
      'text' => 'Текст тестовой теории',
      'link' => 'Ссылка тестовой теории',
      'search' => 'Тестовая',
    ];
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testTheoryPage($I);
    //$this->testAddNewTheory($I);
    $this->testSearchTheory($I);
    //$this->testEditTheory($I);
    //$this->testDeleteTheory($I);
  }

  private function testTheoryPage(AcceptanceTester $I)
  {
    testNavBar($I, true);
    $breadcrumb = ['Домашняя страница', 'Теория'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Теория');
    $I->seeElement('input');
    $I->see('Тема', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      $I->see($this->dataDB[$i]['theme'], 'tbody');
      $i++;
    }
  }

  private function testAddNewTheory(AcceptanceTester $I)
  {
    $I->click('Добавить теорию');
    $breadcrumb = ['Домашняя страница', 'Список тем по теории', 'Добавить новую теорию'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Добавить новую теорию');
    $this->testSeeEdit($I);
    $this->testFillField($I);
    $I->click('Сохранить');
  }

  private function testSearchTheory(AcceptanceTester $I)
  {
    $I->amOnPage('/admin/theory/?page=1');
    $this->testTheoryPage($I);
    $I->fillField('input', $this->newTheory['search']);
    $i = 0;
    $I->wait(0.5);
    while ($i <= count($this->dataDB) - 1) {
      if (strpos($this->dataDB[$i]['theme'], $this->newTheory['search']) === false) {
        $I->dontSee($this->dataDB[$i]['theme'], 'tbody');
        $i++;
      } else {
        $I->see($this->dataDB[$i]['theme'], 'tbody');
        $i++;
      }
    }
  }

  private function testEditTheory(AcceptanceTester $I)
  {

  }

  private function testDeleteTheory(AcceptanceTester $I)
  {
    $I->amOnPage('/admin/theory/?page=1');
    $I->wait(0.5);
    $I->click('input[value="'.$this->nof.'"]');
    $I->click('Удалить');
    $I->dontSee($this->newTheory['theme']);
  }

  private function testSeeEdit(AcceptanceTester $I)
  {
    $I->see('Тема', 'b');
    $I->see('Введите текст теории:', 'b');
    $I->see('Введите ссылку:', 'b');
  }

  private function testFillField(AcceptanceTester $I)
  {
    $I->fillField('theme', 'Тестовая тема');
    $I->wait(0.5);
    $I->fillField(['name' => 'text'], 'Текст тестовой темы');
    $I->fillField(['name' => 'link'], 'Ссылка тестовой темы');
  }
}
