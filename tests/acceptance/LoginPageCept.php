<?php
require_once FUNCTIONS;
$I = new AcceptanceTester($scenario);
$I->wantTo('Test login page');
$I->amOnPage('/login/');
testNavBar($I, true);
$I->see('Вход в систему', 'h1');
testLogin($I, testLogin, testPasswd);