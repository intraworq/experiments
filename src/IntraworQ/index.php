<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once 'config/bootstrap.php';
$language = "pl_PL";
putenv("LANG=" . $language);
setlocale(LC_ALL, $language);

$domain = 'messages';
$path = __DIR__ . '/i18n';
bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $path); 
textdomain($domain);

//definicja cache
$cache = \CacheCache\CacheManager::factory(array(
		'backend' => 'CacheCache\Backends\Memcache',
		'backend_args' => array(array(
				'host' => 'localhost',
				'port' => 11211
			))
	));

$app = new \Slim\Slim([
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views'
	]);
require_once 'app.php';

//$app->run();
?>