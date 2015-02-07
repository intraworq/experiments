<?php 

require __DIR__ . '/../../vendor/autoload.php';
require 'config.php';

const DEBUGBAR_PATH = '/../../vendor/maximebf/debugbar/src/DebugBar/Resources';

$language = "pl_PL";
putenv("LANG=" . $language); 
setlocale(LC_ALL, $language);
 
// Set the text domain as "messages"
$domain = "messages";
bindtextdomain($domain, "src/IntraworQ/i18n"); 
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

use DebugBar\StandardDebugBar;

$builder = new \DI\ContainerBuilder();
// $builder->addDefinitions('injections.php');
$container = $builder->build();

Logger::configure($config['logger']);

$app = new \Slim\Slim([
	'view' => new \Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views'
	]);

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

$app->container->singleton('debugBar', function () use($app, $config){
	$debugBar = new DebugBar\StandardDebugBar();
	//$debugBar->addCollector(new DebugBar\Bridge\Log4PhpCollector($pp->log));
	return $debugBar;
});

$container->set('App', $app);

$app->get('/', function () use($app) {
	$app->log->debug("/ route");
    $app->render('index.tpl', ['debugbarRenderer'=>$app->debugBar->getJavascriptRenderer(DEBUGBAR_PATH)]);
});

$app->get('/test', function() {
	echo 'test';
});

$app->run();