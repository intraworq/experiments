<?php

require __DIR__ . '/../../vendor/autoload.php';
require 'config.php';

	const DEBUGBAR_PATH = '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';

use DebugBar\StandardDebugBar;

$debugBar = $debugbar = new DebugBar\StandardDebugBar();

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

$app->container->singleton('log',
	function () use($config) {
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');

	return $log;
});

$app->container->singleton('message', function () use($debugBar) {
	return $debugBar->getCollector('messages');
});

$app->container->singleton('time', function () use($debugBar) {
	return $debugBar->getCollector('time');
});

$app->container->singleton('exceptions', function () use($debugBar) {
	return $debugBar->getCollector('exceptions');
});

$app->container->singleton('db',
	function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
$debugBar->addCollector(new \Lib\log4phpCollector($app->log));

$app->container->set('debugBar', $debugBar);

$app->get('/',
	function () use($app) {
	$app->log->debug("/ route");
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->get('/notes',
	function () use($app) {
	/** sample mesages to debugbar log4pp tab * */
	$app->log->debug("/notes route debug");
	$app->log->error("error");
	$app->log->fatal("fatal error ");
	$app->log->warn("warning");
	$app->log->info("info");

	//database log query example
	$stmt = $app->db->prepare("SELECT * FROM tebook");
	$stmt->execute();
	$app->log->debug(json_encode($stmt->fetchAll()));
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->get('/message/(:news)',
	function ($news = 'Wiadomość') use($app) {
	$app->message->addMessage($news);
	$app->time->startMeasure('longop', 'Start message');
	sleep(2);
	$app->time->stopMeasure('longop');
	$app->time->measure('In function sleep', function() {
		sleep(2);
	});
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});

$app->get('/exceptions',
	function () use($app) {
	try {
		$t = 5 / 0;
	} catch (Exception $e) {
		$app->exceptions->addException($e);
	}


	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->run();
