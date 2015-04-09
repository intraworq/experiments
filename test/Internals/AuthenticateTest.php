<?php

use Symfony\Component\DomCrawler\Crawler;

class AuthenticateTest extends LocalWebTestCase {

	private static $storageData;

	public function test_workflow_authenticate() {
		$this->client->post('/login',['username'=>'workflow','password'=>'rasmuslerdorf']);
		$this->assertEquals(302, $this->client->response->status());
		self::$storageData = $this->app->auth->getStorage()->read();
	}

	public function test_is_admin_authenticated() {
		$this->app->auth->getStorage()->write(self::$storageData);
		$this->client->get('/guest');
		$this->assertEquals(200, $this->client->response->status());

		$crawler = new Crawler($this->client->response->body());

		$this->assertEquals('mam dostęp do edycji', $crawler->filterXPath('html/body/div[1]')->text());
	}

	public function test_logout() {
		$this->app->auth->getStorage()->write(self::$storageData);
		$this->client->get('/logout');
		$this->assertNull($this->app->auth->getStorage()->read());
		$this->assertEquals(302, $this->client->response->status());
	}
}
