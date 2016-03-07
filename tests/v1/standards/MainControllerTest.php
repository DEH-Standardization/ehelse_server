<?php

require_once "../EHelseTestCase.php";
require_once "../../../src/v1/main/controllers/MainController.php";

class MainControllerTest extends EHelseTestCase
{


    public function testGetResponse__path_standards__method_get__body_empty__returns_standards()
    {
        $standard_controller = new MainController($path = [], $method = "GET", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("I returned all the stds!", $response);
    }

}
