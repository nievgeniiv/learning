<?php

require_once FUNCTIONS;

class StudentHelpPageCest
{
  private $dataDB;
  private $newHelp;

  public function _before(AcceptanceTester $I)
  {
    $this->newHelp = [
      'whose' => md5('Ни Евгений Вячеславович'),
      'search' => 'Тестовый',
      'message' => 'Тестовый текст помощи'
    ];
    testLogin($I, testLogin, testPasswd);
    $I->click('Помощь');
    $db = new ModelHelp;
    $this->dataDB = $db->getFilterData($this->newHelp['whose'], 'all', 1);
    $I->wantTo('Test Help page for Student');
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testHelpPage($I);
    $this->testAddNewMessage($I);
    $this->testSearch($I);
  }

  private function testHelpPage(AcceptanceTester $I)
  {
    testNavBar($I);
    $breadcrumb = ['Помощь'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Помощь');
    $I->see('Сообщение', 'thead');
    $I->see('Отвечено', 'thead');
    $I->see('Дата последнего изменения', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      $I->see($this->dataDB[$i]['message'], 'tbody');
      if (!empty($this->dataDB[$i]['answer'])) {
        $I->see('Да', 'tbody');
      }
      $I->see($this->dataDB[$i]['dateChange'], 'tbody');
      $i++;
    }
  }

  private function testAddNewMessage(AcceptanceTester $I)
  {
    $I->click('Задать новый вопрос');
    testNavBar($I);
    $breadcrumb = ['Помощь', 'Вопрос'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Вопрос');
    $I->see('Введите свой вопрос');
    $I->fillField('message', $this->newHelp['message']);
    $I->click('Отправить');
    $I->wait(0.5);
    $I->click('Вперед');
    $I->wait(0.5);
    $I->see($this->newHelp['message'], 'tbody');
  }

  private function testSearch(AcceptanceTester $I)
  {
    $I->click('Помощь');
    $I->fillField('input', $this->newHelp['search']);
    $i = 0;
    $I->wait(0.5);
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      if (strpos($this->dataDB[$i]['message'], $this->newHelp['search']) === 0) {
        $I->see($this->dataDB[$i]['message'], 'tbody');
      }
      $I->dontSee($this->dataDB[$i]['message'], 'tbody');
      $i++;
    }
  }
}
