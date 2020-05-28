<?php

class Application
  extends Controller
 {

  /** @noinspection MagicMethodsValidityInspection */
  /** @noinspection PhpMissingParentConstructorInspection */
  public function __construct() {
	}


		/*

			GET   /                    страница приветствия

			GET   /login/              форма логина
			POST  /login/do/           форма логина

			Страницы администратора
			GET   /admin/              дашбоард, пока непонятно

			GET   /admin/theory/       список тем
			GET   /admin/theory/add/   форма добавления темы
			POST  /admin/theory/save/  сохранение темы

			GET   /admin/practic/       список заданий
			GET   /admin/practic/add/   форма добавления задания
			POST  /admin/practic/save/  сохранение задания

			GET   /admin/help/          сообщения
			GET   /admin/help/add/      добавление сообщения
			POST  /admin/help/save/     сохранение сообщения

			Страницы студента
			GET   /theory/              список тем
			GET   /theory/<code>/       текст темы

			GET   /practic/             студент, список заданий
			GET   /practic/<code>/      текст примера, задания и коментария
			GET   /practic/<code>/add/  добавть ответ
			POST  /practic/<codr>/save/ сохранение ответ

			GET   /help/                 сообщения
			GET   /help/add/             добавление сообщения
			POST  /help/save/            сохранение сообщения

		*/




	public function run() {
		session_start();
		// Получить данные
		$route = Route::getInstance();

		// Проверить данные
    $controller = $route->go();
		// Делегируем выполнение в контроллер
		$controller->run();
	}


}
