<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/link-category/controllers/LinkCategoryController.php";

class LinkCategoryTest extends EHelseDatabaseTestCase
{
    const list_name = "linkCategories";
    const fields = ["id", "name", "description"];

    public function test_get_all_link_category_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_link_category_table.xml");
        $controller = new LinkCategoryController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    public function test_get_all_link_categories_on_basic_table_returns_statues()
    {
        $this->mySetup(__DIR__ . "/basic_link_category_table.xml");
        $controller = new LinkCategoryController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    public function test_get_link_category_on_empty_table_returns_error()
    {
        $this->mySetup(__DIR__ . "/empty_link_category_table.xml");
        $controller = new LinkCategoryController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    public function test_get_link_category_on_basic_table_returns_link_category()
    {
        $this->mySetup(__DIR__ . "/basic_link_category_table.xml");
        $controller = new LinkCategoryController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);
    }

    public function test_delete_link_category_returns()
    {
        $this->mySetup(__DIR__ . "/basic_link_category_table.xml");
        $controller = new LinkCategoryController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    public function test_update_link_category_updated_link_category()
    {
        $this->mySetup(__DIR__ . "/basic_link_category_table.xml");
        $new_data = [
            "id" => 1,
            "name" => "123",
            "description" => "321"
        ];
        $controller = new LinkCategoryController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    public function test_post_link_category_creates_link_category()
    {
        $this->mySetup(__DIR__ . "/basic_link_category_table.xml");
        $new_data = [
            "name" => "derp",
            "description" => "pred"
        ];
        $controller = new LinkCategoryController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }
}
