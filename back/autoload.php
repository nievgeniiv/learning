<?php

spl_autoload_register(function ($classname) {

	$folder = __DIR__ . '/';

  if (!file_exists($folder . 'Route.php')) {
    $folder = __DIR__ . '/../back/';
  }

	if (strpos($classname, 'Controller') === 0) {
		$folder .= 'controllers/';
	} else if (strpos($classname, 'Model') === 0) {
		$folder .= 'models/';
	} else if (strpos($classname, 'View') === 0) {
		$folder .= 'views/';
	} else if (strpos($classname, 'Service') === 0) {
    $folder .= 'services/';
  } else if (strpos($classname, 'Validator') === 0){
	  $folder .= 'services/';
  }

  $filename = $folder . $classname . '.php';

	if (file_exists($filename)) {
		/** @noinspection PhpIncludeInspection */
		require_once $filename;
	}
});

