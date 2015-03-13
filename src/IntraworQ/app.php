<?php

require 'config.php';

//cookie
$app->add(new \Slim\Middleware\SessionCookie(array(
	'expires' => '20 minutes',
	'path' => '/',
	'domain' => null,
	'secure' => false,
	'httponly' => false,
	'name' => 'slim_session',
	'secret' => 'CHANGE_ME',
	'cipher' => MCRYPT_RIJNDAEL_256,
	'cipher_mode' => MCRYPT_MODE_CBC
)));

use DebugBar\StandardDebugBar;
use IntraworQ\Models;
use IntraworQ\Controllers;
use IntraworQ\Library;

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/injections.php');
$container = $builder->build();


Logger::configure($config['logger']);

require_once 'config/container.php';

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
);
$view->getInstance()->assign('debugbarRenderer', $app->config('debug') ? $app->debugBar->getJavascriptRenderer() : null);

if ($app->config('debug')) {
	$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));

//doctrine
$debugStack = new \Doctrine\DBAL\Logging\DebugStack();
$entityManager->getConnection()->getConfiguration()->setSQLLogger($debugStack);
$app->debugBar->addCollector(new DebugBar\Bridge\DoctrineCollector($entityManager));
//pdo
$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
$app->debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
}
$container->set('App', $app);
$router = new \IntraworQ\Library\Router($container);

$routes = array(
	'/' => 'Main:index@get',
	'/hello/:name' => 'User:index@get'
);

$router->addRoutes($routes);
$router->set404Handler("Main:_404");
$router->run();
//require_once 'config/router.php';
