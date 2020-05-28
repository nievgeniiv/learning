<?php

function writeFile($str)
{
  $fd = fopen(LOG . "log.txt", 'a') or die("не удалось создать файл");
  $data = date('d.M.Y H:i:s') . ' ' . $str . "\n";
  fwrite($fd, $data);
  fclose($fd);
}

function getMaxId(string $table)
{
  $db = DB::getInstance();
  $sql = 'SELECT MAX(id) FROM ' . $table;
  return $db->getCell($sql, '')['MAX(id)'];
}

function getNof(string $table)
{
  $db = DB::getInstance();
  $sql = 'SELECT COUNT(*) FROM ' . $table;
  return $db->getRow($sql, '')['COUNT(*)'];
}

function testArray(UnitTester $tester, array $data, array $dataDB)
{
  foreach ($data as $key => $value) {
    if ($key != 'id' and $key != 'dateChange') {
      $tester->assertEquals($data[$key], $dataDB[$key]);
    }
  }
}

function testNavBar(AcceptanceTester $I, bool $admin = false)
{
  if ($admin === false) {
    $I->see('Теория', 'nav');
    $I->seeLink('Теория', '/theory/?page=1');
    $I->see('Практика', 'nav');
    $I->seeLink('Практика', '/practic/?page=1');
    $I->see('Помощь', 'nav');
    $I->seeLink('Помощь', '/help/?page=1');
    $I->see('Примеры', 'nav');
    $I->seeLink('Примеры', '/simple/');
  } else {
    $I->see('Главная', 'nav');
    $I->seeLink('Главная', '/admin/');
    $I->see('Теория', 'nav');
    $I->seeLink('Теория', '/admin/theory/?page=1');
    $I->see('Практика', 'nav');
    $I->seeLink('Практика', '/admin/practic/list/?page=1');
    $I->see('Помощь', 'nav');
    $I->seeLink('Помощь', '/admin/help/list/?page=1');
  }
}

function testLogin(AcceptanceTester $I, string $username, string $passwd)
{
  $I->amOnPage('/login/');
  $I->fillField('username', $username);
  $I->fillField('passwd', $passwd);
  $I->click('Войти');
}

function testBreadcrumb(AcceptanceTester $I, array $arrayBreadcrumb)
{
  foreach ($arrayBreadcrumb as $value) {
    $I->see($value, 'nav');
  }
}

function testHeader(AcceptanceTester $I, string $text)
{
  $I->see($text, 'h1');
}

function testPagination(AcceptanceTester $I, array $pages)
{
  foreach ($pages as $page) {
    if ($page === 1) {
      $I->seeElement('li', '.page-item disabled');
    } else {
      $I->see('Назад', '.page-link');
    }
  }
  if (count($pages) === 0) {
    $I->see('Назад', '.page-link');
  }
}

function getDataPage(string $model, int $page)
{
  $db = new $model;
  return $db->getData($page);
}