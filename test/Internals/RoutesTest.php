<?php
use Symfony\Component\DomCrawler\Crawler;

class RoutesTest extends LocalWebTestCase
{
	public function test_get_main_route_pl_PL() {
		$language = "en_US";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/');

		$crawler = new Crawler($this->client->response->body());

		$this->assertEquals('Witaj!To my site', $crawler->filterXPath('html/body/h1')->text());
		$this->assertEquals(200, $this->client->response->status());
		$this->assertRegExp("/To my site/", $this->client->response->body());
	}

	public function test_get_main_route_en_US() {
		$language = "en_US";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/');
		$this->assertEquals(200, $this->client->response->status());
		$this->assertRegExp("/To my site/", $this->client->response->body());
	}
	public function test_get_route_access_deny() {
		$this->client->get('/hello/George');
		$this->assertEquals(302, $this->client->response->status());
	}

	public function test_not_found_site() {
		$language = "pl_PL";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/greeta/George');
		$this->assertEquals(404, $this->client->response->status());
	}

	public function test_localization_for_route_en_US() {
		$language = "en_US";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->app->acl->allow('guest');
		$this->client->get('/greet/George');
		$this->assertRegExp("/George/", $this->client->response->body());
	}

	public function test_post_route() {
		$data = ['name' => 'George'];
		//$payload =  json_encode($data);
		$this->client->app->acl->allow('guest');
		$this->client->post('/user', $data);

		$this->assertRegExp("/George created/", $this->client->response->body());
	}
}