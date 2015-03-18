<?php
use DebugBar\StandardDebugBar;
use Respect\Validation\Validator as v;
use mikehaertl\wkhtmlto\Pdf;

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/injections.php');
$container = $builder->build();

$app->container->singleton('db', function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

$app->container->singleton('log', function () use($config){
	Logger::configure($config['logger']);
	return Logger::getLogger('planq');
});

$app->container->singleton('faker', function () use($config){
	$faker = Faker\Factory::create();
	return $faker;
});
$app->container->singleton('v', function () use($config){
	return v::create();
});

$app->container->singleton('router', function () use($config,$container){
	return new IntraworQ\Library\IwqRouter($container);
});

$app->container->singleton('debugBar', function () use($app, $config){
	$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
	$debugBar = new DebugBar\StandardDebugBar();
	$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
	return $debugBar;
});