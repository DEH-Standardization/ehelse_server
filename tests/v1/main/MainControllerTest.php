<?php

require_once __DIR__ ."/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/MainController.php";

class MainControllerTest extends EHelseTestCase
{


    public function testGetResponse__path_standards__method_get__body_empty__returns_standards()
    {
        $standard_controller = new MainController($path = ["","v1","standards"], $method = "GET", $body = "");
        $response = $standard_controller->getResponse();
        $this->assertEquals(Response::STATUS_CODE_OK, $response->getResponseCode());
        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
    }

}
