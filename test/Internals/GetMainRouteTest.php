<?php

class GetMainRoute extends LocalWebTestCase
{
    public function test_get_main_route()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->status());
        $this->assertRegExp("/Hello, world/", $this->client->response->body());
    }
}