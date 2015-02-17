<?php

require __DIR__ . '/../../vendor/autoload.php';
require 'config.php';


	const DEBUGBAR_PATH = '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';

use DebugBar\StandardDebugBar;
use League\Period\Period;

//definicja cache
$cache = \CacheCache\CacheManager::factory(array(
		'backend' => 'CacheCache\Backends\Memcache',
		'backend_args' => array(array(
				'host' => 'localhost',
				'port' => 11211
			))
	));

//konfiguracja slim
$app = new \Slim\Slim([
	'view' => new \Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views',
	]);
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

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
);
//debuger php
$debugBar = $debugbar = new DebugBar\StandardDebugBar();
$debugBar->getCollector('messages')->addMessage($debugBar->getCollector('php')->collect());
$debugBar->addCollector(new DebugBar\Bridge\CacheCacheCollector($cache));

//kontenery
$app->container->singleton('log',
	function () use($config) {
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');

	return $log;
});

$app->container->singleton('message', function () use($debugBar) {
	return $debugBar->getCollector('messages');
});

$app->container->singleton('cache', function () use($cache) {
	return $cache;
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
//DEBUGER BAZY
$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
$debugBar->addCollector(new \Lib\log4phpCollector($app->log));

$app->container->set('debugBar', $debugBar);

//STRONY
$app->get('/',
	function () use($app) {
	$app->log->debug("/ route");
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->get('/setCache',
	function () use($app) {
	//test cache
	$object = new StdClass;
	$object->attribute = 'test';
	$app->cache->set('object', $object);
	$app->cache->set('array', array('sadf'));
	$app->cache->set('string', 'fsd');

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->get('/cache',
	function () use($app) {
	$app->cache->get('object');
	$app->message->addMessage($app->cache->get('object'));
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});
$app->get('/leaguePeriod',
	function () use($app) {

	$period = Period::createFromDuration('2014-10-03 08:12:37', 3600);
	$period2 = Period::createFromDuration('2014-10-03 08:12:37', 7200);
	$start = $period->getStart(); //return the following DateTime: DateTime('2014-10-03 08:12:37');
	$end = $period->getEnd(); //return the following DateTime: DateTime('2014-10-03 09:12:37');
	$duration = $period->getDuration(); //return a DateInterval object
	$duration2 = $period->getDuration(true); //return the same interval expressed in seconds.
	dump($period->diff($period2));
	dump(Period::createFromMonth(2015, 2));
	dump(Period::createFromQuarter(2015, 1));
	dump(Period::createFromSemester(2015, 1));
	dump(Period::createFromWeek(2015, 22));
	dump(Period::createFromYear(2015));
	dump($period->intersect($period2));
	dump(array($start, $end));


	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});

$app->get('/phpPeriod',
	function () use($app) {

	$pp = new PHPeriod\Period(new DateTime("2012-07-08 11:14:15.638276"), new DateTime("2012-07-09 11:14:15.638276"));
	$pp2 = new PHPeriod\Period(new DateTime("2012-07-08 12:14:15.638276"), new DateTime("2012-07-09 11:14:15.638276"));
	$p = new \PHPeriod\PeriodCollection();
	$p->append($pp);
	$p->append($pp2);
	dump($p);

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
