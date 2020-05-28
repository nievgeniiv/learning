<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;

class ControllerLoginTest extends Unit
{
  /**
   * @var \UnitTester
   */
  protected $tester;
  private $controller;
  private $user;

  protected function _before()
  {
    $this->user = new ModelLogin();
    $data = [
      'user' => 'Ivanov',
      'passwd' => 'ivanov',
      'name' => 'Иванов Иван Иванович',
      'email' => 'ivanov@mail.ru',
      'identificate' => 'Иванов Иван Иванович',
      'access' => 'admin',
      'id_vk' => '12345',
      'shortName' => 'Иванов И.И.'
    ];
    $this->user->setUser($data);

  }

  protected function _after()
  {
  }

  /**
   * @dataProvider urlProvider
   */
  public function testSomeFeature($url)
  {
    //$this->controller = new ControllerLogin($url[]);
    //$this->controller->run();
    $this->tester->seeInDatabase('users', [
      'user' => 'Ivanov',
      'passwd' => md5('ivanov'),
      'name' => 'Иванов Иван Иванович',
      'email' => 'ivanov@mail.ru',
      'identificate' => md5('Иванов Иван Иванович'),
      'access' => 'admin',
      'id_vk' => '12345',
      'shortName' => 'Иванов И.И.'
    ]);
  }

  public function urlProvider()
  {
    return [
      'login' => ['login', ['']],
      'login/do' => ['login', ['', 'do']],
      'login/out' => ['login', ['', 'out']],
    ];
  }
}