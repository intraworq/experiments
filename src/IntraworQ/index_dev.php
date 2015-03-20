<?php
require_once __DIR__ . '/../../vendor/autoload.php';

xhprof_enable();

$app = new IntraworQ\Library\Slim\Slim([
	'view' => new Slim\Views\Smarty(),
	'templates.path' => __DIR__ . '/Views'
	]);

require_once 'app.php';

$app->run();

$xhprof_data = xhprof_disable();

include_once "vendor\\facebook\\xhprof\\xhprof_lib\\utils\\xhprof_lib.php";
include_once "vendor\\facebook\\xhprof\\xhprof_lib\\utils\\xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_IntraworQ");


?>