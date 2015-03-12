<?php

$app->get('/',
	function () use($app) {
	$app->log->debug("GET: / route");
	$app->log->info("GET: / route");
	$app->log->error("GET: / route");

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/test', function() {
	echo 'test';
});

$app->get('/hello/:name',
	function($name) use($app) {
	$app->log->info("GET: getting /hello/{$name} route");
	echo "Hello, {$name}";
});

$app->get('/greet/:name',
	function($name) use($app) {
	$app->log->info("GET: getting /greet/{$name} route");
	$app->render('hello.tpl', ['name' => $name, 'debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->post('/user',
	function() use($app) {
	$payload = $app->request->post('name');
	$app->log->info("POST: {$payload} created");
	if ($app->request->isAjax()) {
		$app->log->info('got AJAX request');
		$a = ['user' => $payload . ' created'];
		$app->response->write(json_encode($a));
	} else {
		$app->response->write($payload . ' created');
	}
});

$app->get('/user',
	function() use($app) {
	$app->render('user.tpl',
		['user' => new \IntraworQ\Models\User("George"), 'debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/user_ajax',
	function() use($app) {
	$renderer = $app->debugBar->getJavascriptRenderer($app->debugbar_path);
	$app->render('user_ajax.tpl', ['debugbarRenderer' => $renderer]);
});

$app->post('/long1',
	function() use($app) {
	$app->log->info('/long1');
	$app->log->info($app->request->post());
	sleep(1);
	$app->response->write(json_encode(['res' => 'long1']));
});

$app->post('/long2',
	function() use($app) {
	$app->log->info('/long2');
	sleep(1);
	$app->response->write(json_encode(['res' => 'long2']));
});

$app->post('/long3',
	function() use($app) {
	$app->log->info('/long3');
	sleep(1);
	$app->response->write(json_encode(['res' => 'long3']));
});

$app->get('/long',
	function() use($app) {
	$app->render('long.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/chart',
	function() use($app) {
	$app->render('chart.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/progress',
	function() use($app) {
	$app->render('progress.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/setCache',
	function () use($app) {
	//test cache
	$object = new StdClass;
	$object->attribute = 'test';
	$app->cache->set('object', $object);
	$app->cache->set('array', array('sadf'));
	$app->cache->set('string', 'fsd');

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
$app->get('/cache',
	function () use($app) {
	$app->cache->get('object');
	$app->message->addMessage($app->cache->get('object'));
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
$app->get('/leaguePeriod',
	function () use($app) {

	$period = \League\Period\Period::createFromDuration('2014-10-03 08:12:37', 3600);
	$period2 = \League\Period\Period::createFromDuration('2014-10-03 08:12:37', 7200);
	$start = $period->getStart(); //return the following DateTime: DateTime('2014-10-03 08:12:37');
	$end = $period->getEnd(); //return the following DateTime: DateTime('2014-10-03 09:12:37');
	$duration = $period->getDuration(); //return a DateInterval object
	$duration2 = $period->getDuration(true); //return the same interval expressed in seconds.
	dump($period->diff($period2));
	dump(\League\Period\Period::createFromMonth(2015, 2));
	dump(\League\Period\Period::createFromQuarter(2015, 1));
	dump(\League\Period\Period::createFromSemester(2015, 1));
	dump(\League\Period\Period::createFromWeek(2015, 22));
	dump(\League\Period\Period::createFromYear(2015));
	dump($period->intersect($period2));
	dump(array($start, $end));


	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/phpPeriod',
	function () use($app) {

	$pp = new PHPeriod\Period(new DateTime("2012-07-08 11:14:15.638276"), new DateTime("2012-07-09 11:14:15.638276"));
	$pp2 = new PHPeriod\Period(new DateTime("2012-07-08 12:14:15.638276"), new DateTime("2012-07-09 11:14:15.638276"));
	$p = new \PHPeriod\PeriodCollection();
	$p->append($pp);
	$p->append($pp2);
	dump($p);

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/notes',
	function () use($app) {
	/** sample mesages to debugbar log4pp tab * */
	$app->log->debug("/notes route debug");
	$app->log->error("error");
	$app->log->fatal("fatal error ");
	$app->log->warn("warning");
	$app->log->info("info");

	$stmt = $app->db->prepare("SELECT * FROM tebook");
	$stmt->execute();
	$app->message->debug($stmt->fetchAll());
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});
//time line
$app->get('/message/(:news)',
	function ($news = 'Wiadomość') use($app) {
	$app->message->addMessage($news);
	$app->time->startMeasure('longop', 'Start message');
	sleep(2);
	$app->time->stopMeasure('longop');
	$app->time->measure('In function sleep', function() {
		sleep(2);
	});
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/exceptions',
	function () use($app) {
	try {
		$t = 5 / 0;
	} catch (Exception $e) {
		$app->exceptions->addException($e);
	}


	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/create/:name',
	function ($name) use($app) {
	$newProductName = $name;

	$product = new Product();
	$product->setName($newProductName);
	$entityManager = $app->doctrine;
	$entityManager->persist($product);
	$entityManager->flush();

	echo "Created Product with ID " . $product->getId() . "\n";

	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});

$app->get('/list',
	function () use($app) {
	$entityManager = $app->doctrine;
	$productRepository = $entityManager->getRepository('Product');
	$products = $productRepository->findAll();

	foreach ($products as $product) {
		echo sprintf("-%s\n", $product->getName());
		echo'<br>';
	}
	$app->render('index.tpl', ['debugbarRenderer' => $app->debugBar->getJavascriptRenderer($app->debugbar_path)]);
});