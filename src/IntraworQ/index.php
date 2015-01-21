<?php

require __DIR__ . '/../../vendor/autoload.php';
require 'config.php';

const DEBUGBAR_PATH = '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';

use DebugBar\StandardDebugBar;

$debugBar = new DebugBar\StandardDebugBar();


$app = new \Slim\Slim([
	'view' => new \Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views',
	]);

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
);

$app->container->singleton('log', function () use($config) {
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');
	return $log;
});

$app->container->singleton('db', function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));

$app->container->set('debugBar', $debugBar);

$app->get('/', function () use($app) {
	$app->log->debug("/ route");
	//database log query example
	$stmt = $app->db->prepare("SELECT * FROM notes");
	$stmt->execute();

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});

$app->run();
