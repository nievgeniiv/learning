<?php

class ServiceUsers {

	static function isStudentChosen() {
		if (isset($_SESSION['students'])) {
			return true;
		}
		if (isset($_POST['students'])) {
			$_SESSION['students'] = $_POST['students'];
			return true;
		}
		return false;
	}

	static function isUserAccess(string $access) {
		if ($_SESSION['access'] === $access) {
		  unset($_SESSION['url']);
			return true;
		}
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
		$form = new ServiceForm('login');
		$form->setValue('access', 'Не верный логин или пароль');
    redirect('/login/');
	}
}