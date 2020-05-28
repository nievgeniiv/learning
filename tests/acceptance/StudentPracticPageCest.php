<?php

require_once FUNCTIONS;

class StudentPracticPageCest
{
  private $dataDB;
  private $newPractic;

  public function _before(AcceptanceTester $I)
  {
    $this->newPractic = [
      'whose' => md5('Ни Евгений Вячеславович'),
      'search' => 'Защита',
      'answer' => 'Исправленный ответ 1'
    ];
    testLogin($I, testLogin, testPasswd);
    $I->click('Практика');
    //$this->dataDB = getDataPage('ModelAdmPractic', 1);
    $db = new ModelPractic;
    $this->dataDB = $db->getFilterData($this->newPractic['whose'], 'all', 'all', 'all', 1);
    $I->wantTo('Test Practic page for Student');
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testPracticPage($I);
    $this->testSearch($I);
    $this->testAddAnswer($I);
  }

  private function testPracticPage(AcceptanceTester $I)
  {
    testNavBar($I);
    $breadcrumb = ['Практика'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Практика');
    $I->see('Тема', 'thead');
    $I->see('Оценка', 'thead');
    $I->see('Дата последнего изменения', 'thead');
    $I->see('Дата сдачи задания', 'thead');
    $I->see('Задание просрочено', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      $I->see($this->dataDB[$i]['theme'], 'tbody');
      $I->see($this->dataDB[$i]['rating'], 'tbody');
      $I->see($this->dataDB[$i]['dateChange'], 'tbody');
      $I->see($this->dataDB[$i]['date'], 'tbody');
      $i++;
    }
  }

  private function testSearch(AcceptanceTester $I)
  {
    $I->fillField('input', $this->newPractic['search']);
    $I->wait(0.5);
    $i = 0;
    while ($i <= count($this->dataDB) - 1 and $i <= 4) {
      if (strpos($this->dataDB[$i]['theme'], $this->newPractic['search']) === 0) {
        $I->see($this->dataDB[$i]['theme'], 'tbody');
      } else {
        $I->dontSee($this->dataDB[$i]['theme'], 'tbody');
      }
      $i++;
    }
  }

  private function testAddAnswer(AcceptanceTester $I)
  {
    $I->click('Практика');
    $i = 0;
    $I->wait(0.5);
    $I->click($this->dataDB[$i]['theme']);
    $I->wait(0.5);
    $breadcrumb = ['Практика', $this->dataDB[$i]['theme']];
    testBreadcrumb($I, $breadcrumb);
    $I->see($this->dataDB[$i]['theme'], 'h1');
    $I->see('Пример', 'h4');
    $I->see($this->dataDB[$i]['example'], 'p');
    $I->see('Задание', 'h4');
    $I->see($this->dataDB[$i]['task'], 'p');
    $I->see('Введите ответ или ссылку на bitbucket с ответом:', 'label');
    //$I->see('Отправить', 'form[button]');
    $I->seeInField('input', $this->dataDB[$i]['answer'] ?? '');
    $I->see('Комментарии', 'h4');
    $I->see($this->dataDB[$i]['comments'] ?? '', 'p');
    $I->fillField('input', $this->newPractic['answer']);
    $I->click('Отправить');
    $I->wait(0.5);
    $I->click($this->dataDB[$i]['theme']);
    $I->wait(0.5);
    $I->seeInField('input', $this->dataDB[$i]['answer']);
  }
}
