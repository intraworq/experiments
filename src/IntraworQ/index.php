<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$app = new IntraworQ\Library\Slim\Slim([
	'debug' => true,
	'debugBar' => true,
	'mode' => 'production',
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views',
	'cookies.encrypt' => true,
	'cookies.secret_key' => 'key',
	]);

require_once 'app.php';

$app->run();
?>