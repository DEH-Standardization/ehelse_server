<?php

require_once "../EHelseTestCase.php";
require_once "../../standard/controllers/StandardController.php";

class StandardControllerTest extends EHelseTestCase
{


    public function testGetResponse__path_empty__method_get__body_empty__returns_standards()
    {
        $standard_controller = new StandardController($path = [], $method = "GET", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("I returned all the stds!", $response);
    }

    public function testGetResponse__path_empty__method_post__body_empty__returns_error()
    {
        $standard_controller = new StandardController($path = [], $method = "POST", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("I created a new std!", $response);
    }

    public function testGetResponse__path_empty__method_put__body_empty__returns_error()
    {
        $standard_controller = new StandardController($path = [], $method = "PUT", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("I created a new std!", $response);
    }

    public function testGetResponse__path_empty__method_delete__body_empty__returns_error()
    {
        $standard_controller = new StandardController($path = [], $method = "DELETE", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("I created a new std!", $response);
    }

    public function testGetResponse__path_containing_standard_id__method_get__body_empty__returns_standard()
    {
        $standard_controller = new StandardController($path = ["1"], $method = "GET", $body = "");
        $response = $standard_controller->getResponse();

        $this->assertEquals("return specific std with given id", $response);
    }

}
