<?php
use IntraworQ\Library\Session as SessionStorage;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

//definicja cache
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/injections.php');
$container = $builder->build();

$cache = \CacheCache\CacheManager::factory(array(
				'backend' => 'CacheCache\Backends\Memcache',
				'backend_args' => array(array(
				'host' => 'localhost',
				'port' => 11211
			))
	));
//kontenery
$app->container->singleton('debugBar', function () use($app) {
	$debugBar = new DebugBar\StandardDebugBar();
	return $debugBar;
});

$app->container->singleton('log',
	function () use($config) {
	Logger::configure($config['logger']);
	$log = Logger::getLogger('planq');

	return $log;
});

$app->container->singleton('message', function () use($app) {
	return $app->debugBar->getCollector('messages');
});

$app->container->singleton('cache', function () use($cache) {
	return $cache;
});

$app->container->singleton('time', function () use($app) {
	return $app->debugBar->getCollector('time');
});

$app->container->singleton('exceptions', function () use($app) {
	return $app->debugBar->getCollector('exceptions');
});

//$app->container->singleton('doctrine', function () use($entityManager) {
//	return $entityManager;
//});


$app->container->singleton('db', function () use($config) {
	$pdo = new PDO($config['pdo']['dsn'], $config['pdo']['username'], $config['pdo']['password'], $config['pdo']['options']);
	return $pdo;
});

if ($app->config('debug')) {
	$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));

	//doctrine
	$debugStack = new \Doctrine\DBAL\Logging\DebugStack();
	$entityManager->getConnection()->getConfiguration()->setSQLLogger($debugStack);
	$app->debugBar->addCollector(new DebugBar\Bridge\DoctrineCollector($entityManager));
	//pdo
	$pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($app->db);
	$app->debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
}

$app->container->singleton('storage', function () {
	$sessionConfig = new SessionConfig();
	$sessionConfig->setOptions(array(
		'remember_me_seconds' => 60 * 60 * 24 * 7,
		'name' => 'slim-auth-impl',
	));
	$sessionManager = new SessionManager($sessionConfig);
	$storage = new SessionStorage('zend_session', 'data', $sessionManager);
	return $storage;
});

$container->set('App', $app);

$app->container->singleton('router', function () use ($container) {
	return new IntraworQ\Library\Slim\Router($container);
});

$app->container->singleton('acl', function ()  {
	$acl = new \IntraworQ\ACL\Acl();
	return $acl;
});