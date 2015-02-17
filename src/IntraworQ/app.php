<?php 

require 'config.php';

use DebugBar\StandardDebugBar;
use IntraworQ\Models;

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
	return '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';
});

$app->container->singleton('debugBar', function () use($app, $config){
	$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
	
	$debugBar = new DebugBar\StandardDebugBar();
	$debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
	$debugBar->addCollector(new \Lib\log4phpCollector($app->log));	
	return $debugBar;
});
$app->container->singleton('faker', function () use($config){
	$faker = Faker\Factory::create();	
	return $faker;
});

$container->set('App', $app);

$app->get('/', function () use($app) {
	$app->log->debug("GET: / route");
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
	$app->render('hello.tpl', ['name' => $name]);
});

$app->post('/user', function() use($app) {
	$payload = $app->request->post('name');
	$app->log->info("POST: {$payload} created");
	$app->response->write($payload . ' created');
});

$app->get('/user', function() use($app) {
	$app->render('user.tpl', ['user' => new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale), 'debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
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
	$app->log->debug(json_encode($stmt->fetchAll()));
	$app->render('notes.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer()]);
});

$app->get('/userList',	function () use($app) {
	for($i=0;$i<=10;$i++) {
		$users[] = new \IntraworQ\Models\User($app->faker->lastName, $app->faker->firstNameMale,$app->faker->text);	
	}
	$app->render('userList.tpl', ['users' => $users, 'debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});