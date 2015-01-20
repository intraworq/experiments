<?php 

require __DIR__ . '/../../vendor/autoload.php';
require 'config.php';

$builder = new \DI\ContainerBuilder();
// $builder->addDefinitions('injections.php');

$container = $builder->build();

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

$app->container->singleton('log', function () use($config){
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');
	return $log;
});
$app->container->singleton('debugBar', function () use($config){
	$debugBar = new DebugBar\StandardDebugBar();
		
	return $debugBar;
});

$container->set('App', $app);

$app->get('/', function () use($app) {
	$app->log->debug("/ route");
    $app->render('header.tpl',array('debugbarRenderer'=>$app->debugBar->getJavascriptRenderer()));
    $app->render('footer.tpl',array('debugbarRenderer'=>$app->debugBar->getJavascriptRenderer()));
});

$app->run();