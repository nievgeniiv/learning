<?php

require_once FUNCTIONS;

class AdminHomePageCest
{
  private $dataDB;
  private $newStudent;

  public function _before(AcceptanceTester $I)
  {
    testLogin($I, testLoginAdmin, testPasswdAdmin);
    $this->dataDB = getDataPage('ModelStudent', 1);
    $I->wantTo('Test Home page for Admin');
    $this->newStudent = [
      'name' => 'Шенцова Оксана Андреевна',
      'shortName' => 'Шенцова О.А.',
      'passwd' => 'Remington700',
      'user' => 'shentsova',
      'email' => 'shentsova@mail.ru',
      'search' => 'Шенцова'
    ];
  }

  // tests
  public function tryToTest(AcceptanceTester $I)
  {
    $this->testHomePage($I);
    $this->testAddStudent($I);
    $this->testSearch($I);
    $this->testEditStudent($I);
    $this->testDeleteStudent($I);
  }

  private function testHomePage(AcceptanceTester $I)
  {
    testNavBar($I, true);
    $breadcrumb = ['Домашняя страница'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Домашняя страница');
    $I->seeElement('input');
    $I->see('ФИО студента', 'thead');
    $i = 0;
    while ($i <= count($this->dataDB) - 1) {
      $I->see($this->dataDB[$i]['name'], 'tbody');
      $i++;
    }
  }

  private function testAddStudent(AcceptanceTester $I)
  {
    $I->click('Добавить студента');
    $breadcrumb = ['Домашняя страница', 'Добавление нового студента'];
    testBreadcrumb($I, $breadcrumb);
    testHeader($I, 'Создать нового студента');
    $this->testSeeEdit($I);
    $I->see('Редактировать', 'button');
    $I->click('Редактировать');
    $this->testSeeEdit($I);
    $this->testFillField($I);
  }

  private function testSearch(AcceptanceTester $I)
  {
    $I->amOnPage('/admin/');
    $this->testHomePage($I);
    $I->fillField('input', $this->newStudent['search']);
    $I->wait(0.5);
    $i = 0;
    while ($i <= count($this->dataDB) - 1) {
      if ($this->dataDB[$i]['name'] !== $this->newStudent['name']) {
        $I->dontSee($this->dataDB[$i]['name'], 'tbody');
        $i++;
      } else {
        $I->see($this->dataDB[$i]['name'], 'tbody');
        $i++;
      }
    }
  }

  private function testEditStudent(AcceptanceTester $I)
  {
    $I->click($this->newStudent['name']);
    $this->testSeeEdit($I);

    $I->click('Редактировать');
    $this->testEditDataStudent($I);
    $I->click('Сохранить изменения');
  }

  private function testDeleteStudent(AcceptanceTester $I)
  {
    $I->amOnPage('/admin/');
    $I->wait(0.5);
    $I->click('input[value="'.md5($this->newStudent['name']).'"]');
    $I->click('Удалить');
    $I->dontSee($this->newStudent['name']);
  }

  private function testSeeEdit(AcceptanceTester $I)
  {
    $I->see('ФИО (полностью)');
    $I->see('ФИО (инициалы)');
    $I->see('Логин');
    $I->see('Пароль');
    $I->see('Введите email');
    $I->see('Повторите e-mail');
    return;
  }

  private function testFillField(AcceptanceTester $I, bool $add = true)
  {
    if ($add) {
      $I->fillField('name', $this->newStudent['name']);
      $I->fillField('shortName', $this->newStudent['shortName']);
      $I->fillField('passwd', $this->newStudent['passwd']);
      $I->fillField('user', $this->newStudent['user']);
      $I->fillField('email', $this->newStudent['email']);
      $I->fillField('email_replay', $this->newStudent['email']);
      $I->click('Сохранить изменения');
      return;
    }
  }

  private function testEditDataStudent(AcceptanceTester $I)
  {
    $this->newStudent = [
      'name' => 'Ни Оксана Андреевна',
      'shortName' => 'Ни О.А.',
      'passwd' => 'Remington700!',
      'user' => 'shentsova!',
      'email' => 'shentsova!@mail.ru'
    ];
    foreach ($this->newStudent as $key => $value) {
      if ($key !== 'email') {
        $I->fillField($key, $value);
      } else {
        $I->fillField($key, $value);
        $I->fillField('email_replay', $value);
      }
    }
  }
}