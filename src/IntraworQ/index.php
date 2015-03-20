<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$app = new IntraworQ\Library\Slim\Slim([
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views'
	]);

require_once 'app.php';

$app->run();
?>