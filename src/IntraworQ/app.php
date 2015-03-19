<?php

require 'config.php';

use IntraworQ\Models;
use IntraworQ\Library;

include __DIR__ . '/config/containers.php';
include __DIR__ . '/config/routes.php';

$view = $app->view();
$view->parserDirectory = __DIR__ . '/tmp/smarty';
$view->parserCompileDirectory = __DIR__ . '/tmp/compiled';
$view->parserCacheDirectory = __DIR__ . '/tmp/cache';
$view->parserExtensions = array(
	__DIR__ . '/vendor/slim/views/Slim/Views/SmartyPlugins',
	__DIR__ . '/vendor/smarty-gettext/smarty-gettext'
);

$container->set('App', $app);

$app->debugBar->addCollector(new IntraworQ\Library\Log4PhpCollector($app->log));

//Assing default variable debugbarRenderer to all templates
$view->getInstance()->assign('debugbarRenderer',$app->debugBar->getJavascriptRenderer());