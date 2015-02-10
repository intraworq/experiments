<?php

class RoutesTest extends LocalWebTestCase
{
	public function test_get_main_route_pl_PL() {
		$language = "pl_PL";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/');
		$this->assertEquals(200, $this->client->response->status());
		$this->assertRegExp("/Na mojej stronie/", $this->client->response->body());
	}

	public function test_get_main_route_en_US() {
		$language = "en_US";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/');
		$this->assertEquals(200, $this->client->response->status());
		$this->assertRegExp("/To my site/", $this->client->response->body());
	}

	public function test_get_route_with_param() {
		$this->client->get('/hello/George');
		$this->assertEquals(200, $this->client->response->status());
		$this->assertEquals("Hello, George", $this->client->response->body());
	}

	public function test_localization_for_route_pl_PL() {
		$language = "pl_PL";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/greet/George');
		$this->assertRegExp("/Witaj, George/", $this->client->response->body());
	}

	public function test_localization_for_route_en_US() {
		$language = "en_US";
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		$this->client->get('/greet/George');
		$this->assertRegExp("/Howdy, George/", $this->client->response->body());
	}
}