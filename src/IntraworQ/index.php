<?php

require __DIR__ . '/../../vendor/autoload.php';
define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

$language = "en_US";
putenv("LANG=" . $language);
setlocale(LC_ALL, $language);

$domain = 'messages';
$path = __DIR__ . '/i18n';
bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $path); 
textdomain($domain);

$app = new IntraworQ\Library\IwqSlim([
	'view' => new \Slim\Views\Smarty(),
	'debug' => true,
	'mode' => 'development',
	'templates.path' => __DIR__ . '/Views',
	]);

require_once 'app.php';

$app->run();
?>
