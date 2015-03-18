<?php

class GetMainRoute extends LocalWebTestCase
{
	public function test_get_main() {
		$this->client->get('/user/form');
        $this->assertEquals(200, $this->client->response->status());
        $this->assertRegExp("/Create /", $this->client->response->body());
	}

	public function test_get_main_route()
    {
        $this->client->get('/sssadfds');
        $this->assertEquals(200, $this->client->response->status());
//        $this->assertRegExp("/Welcome/", $this->client->response->body());
    }

}