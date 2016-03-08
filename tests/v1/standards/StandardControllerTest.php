<?php

require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/standards/controllers/StandardController.php";


class StandardControllerTest extends EHelseTestCase
{


    public function testGetResponse__path_empty__method_get__body_empty__returns_standards()
    {
        $standard_controller = new StandardController($path = [], $method = "GET", $body = "");
        $response = $standard_controller->getResponse();

    }


}
