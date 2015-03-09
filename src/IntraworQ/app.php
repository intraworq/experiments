<?php

require 'config.php';

use DebugBar\StandardDebugBar;
use IntraworQ\Models;
use IntraworQ\Library;
use Respect\Validation\Validator as v;
use mikehaertl\wkhtmlto\Pdf;

$builder = new \DI\ContainerBuilder();
// $builder->addDefinitions('injections.php');
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

$app->container->singleton('debugBar', function () use($app, $config){
	$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
	$debugBar = new DebugBar\StandardDebugBar();
	$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
	return $debugBar;
});
$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));


$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
);

//Assing default variable debugbarRenderer to all templates
$view->getInstance()->assign('debugbarRenderer',$app->debugBar->getJavascriptRenderer());

/**
 * ROUTING
 */
$app->get('/', function () use($app) {
	$app->log->debug("GET: / route");
	$app->log->info("GET: / route");
	$app->log->error("GET: / route");

    $app->render('index.tpl');
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
	$app->render('hello.tpl', ['name' => $name]);
});

$app->post('/user', function() use($app) {
	$name = $app->request->post('name');
	$firstName = $app->request->post('firstName');
	$user = new \IntraworQ\Models\User($name,$firstName);
	$messages = array();
	$userValidator = $app->v
			->attribute('name', v::string()->length(4,32))
            ->attribute('firstName', v::string()->length(4,32));
	try {
		$userValidator->assert($user);
		$app->log->info("POST: \"{$name} {$firstName}\" created");
	} catch (Respect\Validation\Exceptions\NestedValidationExceptionInterface  $e) {
		$messages = $e->findMessages(
				[
					'firstName.length' =>'{{name}} invalid character length',
					'name.length' =>'{{name}} invalid character length'
				]
			);
		$app->log->error("USER validation FAIL: ".implode(', ',$messages));
	}

	if($app->request->isAjax()) {
		$stmt = $app->db->prepare("SELECT * FROM notes");
		$stmt->execute();
		$notes = $stmt->fetchAll();

		$app->log->info('got AJAX request');
		$a = ['user' => "{$name} {$firstName} created'"];

		$app->debugBar->sendDataInHeaders();
		$app->response->write(json_encode($a));
	} else {
		$app->render('user.tpl', ['messages'=>$messages, 'user' => $user]);
	}
});

$app->get('/user',	function() use($app) {
	$user = new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale);
	$app->render('user.tpl', ['user' => $user]);
});

$app->get('/user_ajax', function() use($app) {
	$renderer = $app->debugBar->getJavascriptRenderer();
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
	$app->render('long.tpl');
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
	$app->render('notes.tpl', ['notes'=>$notes]);
});

$app->get('/pdf',	function () use($app) {
	$pdf = new Pdf('http://google.pl');
	$pdf->saveAs(__DIR__.'\tmp\pdf\new.pdf');

	$app->render('index.tpl');
});

$app->get('/userList',	function () use($app) {
	for($i=0;$i<=10;$i++) {
		$users[] = new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale,$app->faker->text);
	}
	$app->render('userList.tpl', ['users' => $users]);
});
