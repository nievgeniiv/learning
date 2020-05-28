<?php

require_once FUNCTIONS;

class AdminHelpPageCest
{

  private $dataDB;
  private $newHelp;

  public function _before(AcceptanceTester $I)
  {
    testLogin($I, testLoginAdmin, testPasswdAdmin);
    $I->click('Помощь');
    $db = new ModelAdmHelp;
    $this->dataDB = $db->getFilterData('all', 'all', 1);
    $I->wantTo('Test Help page for Admin');
    $this->newHelp = [
      'id' => getMaxId('help'),
      'message' => 'Тестовое сообщение для помщи',
      'answer' => 'Текст тестового примера по практике',
      'whose' => 'user',
      'student' => 'user',
      'search' => 'Тестовое',
    ];
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testHelpPage($I);
    //$this->testAnswerOnQestion($I);
    $this->testSearch($I);
    $this->testDelete($I);
  }

  private function testHelpPage(AcceptanceTester $I)
  {
    testNavBar($I, true);
    $breadcrumb = ['Домашняя страница', 'Помощь'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Помощь');
    $I->see('Сообщение', 'thead');
    $I->see('Студент', 'thead');
    $I->see('Отвечено', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      $I->see($this->dataDB[$i]['message'], 'tbody');
      $I->see($this->dataDB[$i]['student'], 'tbody');
      if (!empty($this->dataDB[$i]['answer']))
      {
        $I->see('Да', 'tbody');
      }
      $i++;
    }
  }

  private function testSearch(AcceptanceTester $I)
  {
    $I->click('Помощь');
    $I->fillField('input', $this->newHelp['search']);
    $i = 0;
    $I->wait(0.5);
    while ($i <= count($this->dataDB) - 1) {
      if (strpos($this->dataDB[$i]['message'], $this->newHelp['search']) === false) {
        $I->dontSee($this->dataDB[$i]['message'], 'tbody');
        $i++;
      } else {
        $I->see($this->dataDB[$i]['message'], 'tbody');
        $i++;
      }
    }
  }

  private function testDelete(AcceptanceTester $I)
  {
    $I->click('Помощь');
    $I->wait(0.5);
    $I->click('Вперед');
    $I->wait(0.5);
    $I->click('input[value="'.$this->newHelp['id'].'"]');
    $I->click('Удалить');
    $I->dontSee($this->newHelp['message']);
  }
}
