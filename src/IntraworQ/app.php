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
	$app->render('user_ajax.tpl', ['debugbarRenderer'=>$app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
