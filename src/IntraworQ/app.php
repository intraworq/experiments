<?php

require 'config.php';

use DebugBar\StandardDebugBar;
use IntraworQ\Models;
use IntraworQ\Library;
use Respect\Validation\Validator as v;

$builder = new \DI\ContainerBuilder();
// $builder->addDefinitions('injections.php');
$container = $builder->build();

Logger::configure($config['logger']);

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
);

$app->container->singleton('db', function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

$app->container->singleton('log', function () use($config){
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');
	return $log;
});

$app->container->singleton('debugbar_path', function() {
	return '/vendor/maximebf/debugbar/src/DebugBar/Resources';
});


$app->container->singleton('debugBar', function () use($app, $config){
	$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
	$debugBar = new DebugBar\StandardDebugBar();
	$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));

	return $debugBar;
});
$app->container->singleton('faker', function () use($config){
	$faker = Faker\Factory::create();
	return $faker;
});

$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));

$container->set('App', $app);

$app->get('/', function () use($app) {
	$app->log->debug("GET: / route");
	$app->log->info("GET: / route");
	$app->log->error("GET: / route");

    $app->render('index.tpl', ['debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/test', function() {
	echo 'test';
});

$app->get('/hello/:name', function($name) use($app) {
	$app->log->info("GET: getting /hello/{$name} route");
	echo "Hello, {$name}";
});

$app->get('/greet/:name', function($name) use($app) {
	$app->log->info("GET: getting /greet/{$name} route");
	$app->render('hello.tpl', ['name' => $name, 'debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->post('/user', function() use($app) {
	$name	   = $app->request->post('name');
	$firstName = $app->request->post('firstName');

	$user = new \IntraworQ\Models\User($name, $firstName);
	$userValidator = v::attribute(
		'name', v::string()->length(1, 30),
		'firstName', v::string()->length(1, 30)
	);

	if ($userValidator->validate($user)) {
		$app->log->info("POST: {$name} created");
	}
	else {
		$app->log->error("USER validation FAIL: " . $userValidator->reportError($user));
	}

	if($app->request->isAjax()) {
		$app->log->info('got AJAX request');
		$a = ['user' => $payload . ' created'];
		$app->response->write(json_encode($a));
	} else {
		$app->render('user.tpl', ['user' => $user, 'debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
	}
});

$app->get('/user',	function() use($app) {
	$user = new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale);
	$app->render('user.tpl', ['user' => $user, 'debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/user_ajax', function() use($app) {
	$renderer = $app->debugBar->getJavascriptRenderer($app->debugbar_path);
	$app->render('user_ajax.tpl', ['debugbarRenderer'=>$renderer]);
});

$app->post('/long1', function() use($app) {
	$app->log->info('/long1');
	$app->log->info($app->request->post());
	sleep(1);
	$app->response->write(json_encode(['res' => 'long1']));
});

$app->post('/long2', function() use($app) {
	$app->log->info('/long2');
	sleep(1);
	$app->response->write(json_encode(['res' => 'long2']));
});

$app->post('/long3', function() use($app) {
	$app->log->info('/long3');
	sleep(1);
	$app->response->write(json_encode(['res' => 'long3']));
});

$app->get('/long', function() use($app) {
	$app->render('long.tpl', ['debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
$app->get('/notes',	function () use($app) {
	/** sample mesages to debugbar log4pp tab **/
	$app->log->debug("/notes route debug");
	$app->log->error("error");
	$app->log->fatal("fatal error ");
	$app->log->warn("warning");
	$app->log->info("info");

	//database log query example
	$stmt = $app->db->prepare("SELECT * FROM notes");
	$stmt->execute();
	$notes = $stmt->fetchAll();
	$app->log->debug(json_encode($notes));
	$app->render('notes.tpl', ['notes'=>$notes,'debugbarRenderer' => $app->debugBar->getJavascriptRenderer()]);
});

$app->get('/userList',	function () use($app) {
	for($i=0;$i<=10;$i++) {
		$users[] = new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale,$app->faker->text);
	}
	$app->render('userList.tpl', ['users' => $users, 'debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
