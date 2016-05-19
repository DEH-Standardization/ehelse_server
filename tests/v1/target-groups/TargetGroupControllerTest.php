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

    public function test_get_all_topics_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController([], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        self::assertIsValidResponseList($response, Response::STATUS_CODE_OK);
    }

    public function test_get_topic_on_empty_table_returns_not_found_error()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController(["1"], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    public function test_get_topic_returns_topic()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $controller = new TargetGroupController(["1"], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        $topic_data = array(
            'id' => 1,
            'name' => 'Apotek',
            'description' => 'desc',
            'abbreviation' => 'A',
            'parentId' => null);

        self::assertIsValidResponse($response, Response::STATUS_CODE_OK);
        self::assertIsCorrectResponseData($response->getBody(), $topic_data);
    }

    public function test_put_topic_updates_topic()
    {
        $this->mySetup(__DIR__ . "/basic_target_group_table.xml");
        $topic_data = array(
            'id' => 1,
            'name' => 'Apotek2',
            'description' => 'desc2',
            'abbreviation' => 'B',
            'parentId' => 2);

        $controller = new TargetGroupController(["1"], Response::REQUEST_METHOD_PUT, $topic_data);
        $response = $controller->getResponse();

        self::assertIsValidResponse($response, Response::STATUS_CODE_OK);
        self::assertIsCorrectResponseData($response->getBody(), $topic_data);
    }






}