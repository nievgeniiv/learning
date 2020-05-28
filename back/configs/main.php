<?php
$path = __DIR__ . '/../../config.php';
if (!file_exists($path)) {
	user_error('Config file ' . $path . ' not found. Please contact admin.');
	exit();
}

require_once __DIR__ . '/../../config.php';

require_once __DIR__ . '/paths.php';

require_once __DIR__ . '/functions.php';

include __DIR__ . '/../autoload.php';