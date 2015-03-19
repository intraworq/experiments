<?php
//definicja cache
$cache = \CacheCache\CacheManager::factory(array(
		'backend' => 'CacheCache\Backends\Memcache',
		'backend_args' => array(array(
				'host' => 'localhost',
				'port' => 11211
			))
	));
//kontenery
$app->container->singleton('debugBar', function () use($app) {
	$debugBar = new DebugBar\StandardDebugBar();
	return $debugBar;
});

$app->container->singleton('log',
	function () use($config) {
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');

	return $log;
});

$app->container->singleton('message', function () use($app) {
	return $app->debugBar->getCollector('messages');
});

$app->container->singleton('cache', function () use($cache) {
	return $cache;
});

$app->container->singleton('time', function () use($app) {
	return $app->debugBar->getCollector('time');
});

$app->container->singleton('exceptions', function () use($app) {
	return $app->debugBar->getCollector('exceptions');
});

//$app->container->singleton('doctrine', function () use($entityManager) {
//	return $entityManager;
//});


$app->container->singleton('db',
	function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

