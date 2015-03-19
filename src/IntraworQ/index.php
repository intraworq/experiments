<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$language = "pl_PL";
putenv("LANG=" . $language);
setlocale(LC_ALL, $language);

$domain = 'messages';
$path = __DIR__ . '/i18n';
bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $path); 
textdomain($domain);



$app = new IntraworQ\Library\Slim\Slim([
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views'
	]);

require_once 'app.php';

$app->add(new IntraworQ\Library\Middleware\mid());
$app->hook('slim.before', function () use ($app) {
	$app->time->startMeasure('longop', 'Start site');
});
$app->run();
?>