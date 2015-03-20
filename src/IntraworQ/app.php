<?php

$language = "pl_PL";
putenv("LANG=" . $language);
setlocale(LC_ALL, $language);

$domain = 'messages';
$path = __DIR__ . '/i18n';
bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $path);
textdomain($domain);

require 'config/bootstrap.php';
require 'config.php';

//cookie
$app->add(new \Slim\Middleware\SessionCookie(array(
	'expires' => '20 minutes',
	'path' => '/',
	'domain' => null,
	'secure' => false,
	'httponly' => false,
	'name' => 'slim_session',
	'secret' => 'CHANGE_ME',
	'cipher' => MCRYPT_RIJNDAEL_256,
	'cipher_mode' => MCRYPT_MODE_CBC
)));

use DebugBar\StandardDebugBar;
use IntraworQ\Models;
use IntraworQ\Controllers;
use IntraworQ\Library;

Logger::configure($config['logger']);

require 'config/container.php';
require 'config/router.php';

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
);

$view->getInstance()->assign('debugbarRenderer', $app->config('debug') ? $app->debugBar->getJavascriptRenderer() : null);

$app->add(new IntraworQ\Library\Middleware\mid());
$app->hook('slim.before', function () use ($app) {
	$app->time->startMeasure('longop', 'Start site');
});