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

/** ROUTING */

/** Notes group **/
$app->group('/notes', function() use ($app) {
	$app->get('/notesList',	'IntraworQ\Controllers\notesController:notesList');	
});
/** end */

/** User group **/
$app->group('/user', function() use ($app) {
	$app->get('/form', 'IntraworQ\Controllers\userController:userForm');
	$app->get('/user_ajax', 'IntraworQ\Controllers\userController:userAjax');
	$app->get('/userList',	'IntraworQ\Controllers\userController:userList');
	$app->map('/save', 'IntraworQ\Controllers\userController:userSave')->via('GET','POST');

});
/** end user group **/

/** Others */
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


$app->get('/pdf',	function () use($app) {
	$pdf = new Pdf('http://google.pl');
	$pdf->setOptions([
        'binary' => "D:\serwer\wkhtmltopdf\bin\wkhtmltopdf.exe" //path to executable file
	]);
	if($pdf->saveAs(__DIR__.'\tmp\pdf\new.pdf')) {
		$app->log->info("PDF file created");
	}
	else {
		$app->log->error($pdf->getError());
	}
	$app->render('index.tpl');
});
