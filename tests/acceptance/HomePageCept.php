<?php
require_once FUNCTIONS;
$I = new AcceptanceTester($scenario);
$I->wantTo('Test home page');
$I->amOnPage('/');
testNavBar($I, true);
$I->see('Добро пожаловать на сайт по обучению веб-программированию', 'h1');
$I->see('Ввести логин и пароль', 'a');
$I->seeLink('Ввести логин и пароль', '/login/');
$I->click('Ввести логин и пароль');
