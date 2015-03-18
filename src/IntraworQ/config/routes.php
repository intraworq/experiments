<?php
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