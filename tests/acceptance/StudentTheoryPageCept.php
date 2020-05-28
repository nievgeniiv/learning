<?php
require_once FUNCTIONS;
$I = new AcceptanceTester($scenario);
$I->wantTo('Test Theory page for student');
testLogin($I, testLogin, testPasswd);
testNavBar($I);
$breadcrumb = ['Список тем по теории'];
testBreadcrumb($I, $breadcrumb);
testHeader($I, 'Список тем по теории');
$I->seeElement('input');
$I->see('Тема', 'thead');
$I->wait(0.5);
$data = getData(1);
$i = 0;
while ($i <= count($data) - 1) {
  $I->see($data[0][$i]['theme'], 'tbody');
  $i++;
}
//$I->click('Назад');
//$I->seeElement('.page-item disabled');
//$I->see('Назад', '.page-link');

function getData(int $page)
{
  $db = new ModelTheory();
  $data[] = $db->getData($page);
  return $data;
}