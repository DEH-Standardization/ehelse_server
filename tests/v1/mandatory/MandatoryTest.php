<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/mandatory/MandatoryController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/NotFoundError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";

class MandatoryTest extends EHelseDatabaseTestCase
{
    const list_name = "mandatory";
    const fields = ["id", "name", "description"];

    public function test_get_all_mandatory_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_mandatory_table.xml");
        $controller = new MandatoryController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    public function test_get_mandatory_on_empty_table_returns_error()
    {
        $this->mySetup(__DIR__ . "/empty_mandatory_table.xml");
        $controller = new MandatoryController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    public function test_get_mandatory_on_basic_table_returns_mandatory()
    {
        $this->mySetup(__DIR__ . "/basic_mandatory_table.xml");
        $controller = new MandatoryController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);
    }


    public function test_delete_mandatory_returns()
    {
        $this->mySetup(__DIR__ . "/basic_mandatory_table.xml");
        $controller = new MandatoryController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    public function test_update_mandatory_updated_mandatory()
    {
        $this->mySetup(__DIR__ . "/basic_mandatory_table.xml");
        $new_data = [
            "id" => 1,
            "name" => "123",
            "description" => "321"
        ];
        $controller = new MandatoryController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    public function test_post_mandatory_creates_mandatory()
    {
        $this->mySetup(__DIR__ . "/basic_mandatory_table.xml");
        $new_data = [
            "name" => "derp",
            "description" => "pred"
        ];
        $controller = new MandatoryController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

}
