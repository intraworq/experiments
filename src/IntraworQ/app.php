<?php 

require 'config.php';

use DebugBar\StandardDebugBar;
use IntraworQ\Models;
use IntraworQ\Controllers;
use IntraworQ\Library;

$builder = new \DI\ContainerBuilder();
// $builder->addDefinitions('injections.php');
$container = $builder->build();
$container->set('App', $app);

Logger::configure($config['logger']);

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
	);

$app->container->singleton('log', function () use($config){
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');
	return $log;
});

$app->container->singleton('debugbar_path', function() {
	return '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';
});

$app->container->singleton('debugBar', function () use($app, $config){
	$debugBar = new DebugBar\StandardDebugBar();
	return $debugBar;
});

$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));


// $router = new \IntraworQ\Library\Router($container);

// $routes = array(
// 	'/' => 'Main:index@get',
// 	'/hello/:name' => 'User:hello@get'
// 	);

// $router->addRoutes($routes);
// $router->set404Handler("Main:_404");
// $router->run();

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
	$payload = $app->request->post('name');
	$app->log->info("POST: {$payload} created");
	if($app->request->isAjax()) {
		$app->log->info('got AJAX request');
		$a = ['user' => $payload . ' created'];
		$app->response->write(json_encode($a));
	} else {
		$app->response->write($payload . ' created');
	}
});

$app->get('/user', function() use($app) {
	$app->render('user.tpl', ['user' => new \IntraworQ\Models\User("George"), 'debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
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

$app->get('/chart', function() use($app){
	$app->render('chart.tpl', ['debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
