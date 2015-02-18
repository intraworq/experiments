<?php
class Example extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
    $this->setBrowser("*chrome");
    $this->setBrowserUrl("http://localhost:8080");
    
  }

  public function test_creating_user_using_ajax()
  {
    $this->open("/user_ajax");
    $this->type("id=name", "test_user");
    $this->click("css=input[type=\"submit\"]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ("test_user created" == $this->getText("id=result")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->verifyText("id=result", "test_user created");
  }
}
?>