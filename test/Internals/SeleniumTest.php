<?php

class SeleniumTest extends \PHPUnit_Extensions_Selenium2TestCase {

	public static $browsers = array(
		array(
			'browserName' => 'firefox',
			'sessionStrategy' => 'shared',
		)
	);

	protected function setUp() {
        $this->setBrowser('firefox');
		$this->shareSession(TRUE);
        $this->setBrowserUrl('http://google.pl/');
	}

    public function test_login_workflow() {
		$this->url('http://experiments.mp/login');
		$username = $this->byXPath('/html/body/form/input[1]');
		$username->value('workflow');
		$submit = $this->byXPath('/html/body/form/input[3]');
		$submit->click();
		$loginInfo = $this->byId('login_info')->text();
		$this->assertEquals('mam dostęp do edycji', $loginInfo);		
	}

    public function test_login_quest() {
		$this->url('http://experiments.mp/login');
		$username = $this->byXPath('/html/body/form/input[1]');
		$username->value('guest');
		$submit = $this->byXPath('/html/body/form/input[3]');
		$submit->click();
		$loginInfo = $this->byId('login_info')->text();
		$this->assertEquals('nie masz dostępu do edycji', $loginInfo);
	}

    public function test_title()
    {
		$this->url('http://experiments.mp');
		$this->assertEquals('IntraworQ - no translation', $this->title());
    }

}