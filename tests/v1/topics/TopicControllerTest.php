<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/topics/controllers/TopicController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/NotFoundError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";

class TopicControllerTest extends EHelseDatabaseTestCase
{
    const list_name = "topics";
    const fields = ["id", "title", "description", "sequence", "comment"];

    /**
     * Test get all empty db
     */
    public function test_get_all_topics_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_topic_table.xml");
        $controller = new TopicController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        self::assertIsValidResponseList($response);
    }

    /**
     * Test topic empty db
     */
    public function test_get_topic_on_empty_table_returns_error()
    {
        $this->mySetup(__DIR__ . "/empty_topic_table.xml");
        $controller = new TopicController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    /**
     * Test get all topics
     */
    public function test_get_all_topics_on_basic_table_returns_topics()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $controller = new TopicController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        self::assertIsValidResponseList($response);
    }

    /**
     * Test get topic
     */
    public function test_get_topic_on_basic_table_returns_topic()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $controller = new TopicController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        self::assertIsValidResponse($response);
    }

    /**
     * Test delete topic without documents
     */
    public function test_delete_topic_returns()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $controller = new TopicController([0], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    /**
     * Test delete topic with documents
     */
    public function test_delete_topic_with_document_under_returns()
    {
        $this->mySetup(__DIR__ . "/topic_table_with_document.xml");
        $controller = new TopicController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_BAD_REQUEST);
    }


    /**
     * Test put topic
     */
    public function test_update_topic_updated_topic()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "123",
            "description" => "321",
            "sequence" => "1",
            "comment" => "comment"
        ];
        $controller = new TopicController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test post topic
     */
    public function test_post_topic_creates_topic()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $new_data = [
            "title" => "derp",
            "description" => "pred",
            "sequence" => "3",
            "comment" => "comment"
        ];
        $controller = new TopicController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test post subtopic
     */
    public function test_post_subtopic_creates_topic()
    {
        $this->mySetup(__DIR__ . "/basic_topic_table.xml");
        $new_data = [
            "title" => "derp",
            "description" => "pred",
            "sequence" => "3",
            "comment" => "comment",
            "parentId" => "1"
        ];
        $controller = new TopicController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /*
     * Test change sequence
     */
    public function test_change_sequence_for_topic()
    {
        $this->mySetup(__DIR__ . "/different_sequence_topic_table.xml");
        $controller = new TopicController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        $json = json_decode($response->getBody(), true);
        $sequence_element_1 = $json['topics'][0]['sequence'];
        $sequence_element_2 = $json['topics'][1]['sequence'];

        self::assertEquals(1, $sequence_element_2, 'Topic 2, first element');
        self::assertEquals(2, $sequence_element_1, 'Topic 1, second element');
    }

}
