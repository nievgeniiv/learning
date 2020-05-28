<?php

use Codeception\Test\Unit;

require_once FUNCTIONS;
class UserTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $user;
    
    protected function _before()
    {
      //require_once __DIR__ . '/../../back/models/ModelForTest.php';
      $this->user = new ModelForTest();
      $this->user->setAge(33);
    }

    protected function _after()
    {
    }


  //Без этого провайдер не работает
    /**
   * @dataProvider userProvider
   */
    public function testAge($age)
    {
      $this->assertEquals($age, $this->user->getAge()); //проверяет совпадают между сабой параметры.
                                                                //Если нет то тест будет завершен с ошибкой
    }

  public function testAge1()  //первый тест от которого зависит testAge2
  {
    $this->assertEquals(33, $this->user->getAge());
    return 33;
  }


  //Указывает, что данный тест зависит от testAge1
  /**
   * @depends testAge1
   */
  public function testAge2($age) //Этот тест завист от testAge1
  {
    $this->assertEquals($age, $this->user->getAge());
    return 33;
  }

    public function userProvider()  //Провайдер наборы данных, которые принимают наши тесты
    {
      return [
      //  'one' => [1],
      //  'two' => [2],
        'correct' => [33],
      ];
    }
}