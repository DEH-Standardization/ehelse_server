<?php


require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/target-groups/controllers/TargetGroupController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/NotFoundError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";

class TargetGroupControllerTest extends EHelseDatabaseTestCase
{

    const list_name = "targetGroups";
    const fields = ['id', 'name', 'description', 'parentId', 'abbreviation'];

    /**
     * Test get all empty db
     */
    public function test_get_all_topics_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController([], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        self::assertIsValidResponseList($response, Response::STATUS_CODE_OK);
    }

    /**
     * Test get target group empty db
     */
    public function test_get_target_group_on_empty_table_returns_not_found_error()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    /**
     * Test get all target groups
     */
    public function test_get_all_target_groups_on_basic_table_returns_statues()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $controller = new TargetGroupController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        self::assertIsValidResponseList($response);
    }

    /**
     * Test get target group
     */
    public function test_get_target_group_on_basic_table_returns_target_group()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $controller = new TargetGroupController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        
        self::assertIsValidResponse($response);
    }

    /**
     * Test delete target group
     */
    public function test_delete_target_group_returns()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $controller = new TargetGroupController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    /**
     * Test put target group
     */
    public function test_update_target_group_updated_target_group()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $new_data = [
            "id" => 1,
            "name" => "123",
            "description" => "321",
            "abbreviation" => "1"
        ];
        $controller = new TargetGroupController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test post target group
     */
    public function test_post_target_group_creates_target_group()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $new_data = [
            "name" => "derp",
            "description" => "pred",
            "abbreviation" => "d"
        ];
        $controller = new TargetGroupController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

}