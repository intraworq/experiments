<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$app = new IntraworQ\Library\Slim\Slim([
	'debug' => true,
	'debugBar' => true,
	'mode' => 'production',
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views',
	'cookies.encrypt' => true,
	'sessions.cookie' => 'slim_auth',
    'sessions.driver' => 'file', // or database
    'sessions.files' => __DIR__ . '/tmp/sessions',
	]);

require_once 'app.php';

$app->run();
?>