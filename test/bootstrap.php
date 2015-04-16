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
			'SERVER_NAME' => 'local.dev',
			'mode' => 'testing'
		)));

		$app = new IntraworQ\Library\Slim\Slim([
			'view' => new \Slim\Views\Smarty(),
			'templates.path' => PROJECT_ROOT . '/src/IntraworQ/Views',
			'debug' => false
		]);

		// Include our core application file
		require PROJECT_ROOT . '/src/IntraworQ/app.php';

		return $app;
	}
};

class LocalWebTestCaseWithSession extends LocalWebTestCase {

	public static $storage;

	public function setup() {
		parent::setup();
		if (!is_null(self::$storage)) {
			$this->app->auth->getStorage()->write(self::$storage);
		}
	}

	public function tearDown() {
		self::$storage = $this->app->auth->getStorage()->read();
	}

	public function getStorage() {
		return self::$storage;
	}

}