<?php
namespace IntraworQ\Library\Middleware;

class mid extends \Slim\Middleware {

	public function call() {
		// Get reference to application
		$app = $this->app;

		// Run inner middleware and application
		$this->next->call();

		// Capitalize response body
		$res = $app->response;
		$body = $res->getBody();
		$res->setBody($body . '<br/>Middleware');
	}

}
