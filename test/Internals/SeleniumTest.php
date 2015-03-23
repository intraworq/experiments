<?php

class SeleniumTest extends \PHPUnit_Extensions_Selenium2TestCase {

	protected function setUp() {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://google.pl/');
	}

    public function testTitle()
    {
		$this->url('http://google.pl');
		$this->assertEquals('Google', $this->title());
    }

}