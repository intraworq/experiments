<?php
// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

use There4\Slim\Test\WebTestCase;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';

// Initialize our own copy of the slim application
class LocalWebTestCase extends WebTestCase {
	public function getSlimInstance() {
		
		\Slim\Environment::mock(array_merge(array(
			'SERVER_NAME'    => 'local.dev',
			'mode'    => 'testing'
			)));

      // Include our core application file
		require PROJECT_ROOT . '/src/IntraworQ/app.php';

		return $app;
	}
};
