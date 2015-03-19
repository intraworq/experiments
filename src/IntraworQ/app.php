<?php

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