<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/documents/controllers/DocumentFieldController.php";
require_once __DIR__ . "/../../../src/v1/responses/ResponseController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/NotFoundError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";

class DocumentFieldTest extends EHelseDatabaseTestCase
{
    const list_name = "documentFields";
    const fields = ["id", "name", "description", "sequence", "mandatory", "documentTypeId"];

    /**
     * Test all empty db
     */
    public function test_get_all_document_fields_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_document_field_table.xml");
        $controller = new DocumentFieldController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    /**
     * Test get document field empty db
     */
    public function test_get_document_field_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_document_field_table.xml");
        $controller = new DocumentFieldController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    /**
     * Test get all document fields
     */
    public function test_get_all_document_fields_on_basic_table_returns_statues()
    {
        $this->mySetup(__DIR__ . "/basic_document_field_table.xml");
        $controller = new DocumentFieldController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    /**
     * Test get document field
     */
    public function test_get_document_field_on_basic_table_returns_document_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_field_table.xml");
        $controller = new DocumentFieldController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);
    }

    /**
     * Test delete document field
     */
    public function test_delete_document_field_returns()
    {
        $this->mySetup(__DIR__ . "/basic_document_field_table.xml");
        $controller = new DocumentFieldController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    /**
     * Test put document field
     */
    public function test_update_document_field_updated_document_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_field_table.xml");
        $new_data = [
            "id" => 1,
            "name" => "123",
            "description" => "123",
            "sequence" => "1",
            "mandatory" => "1",
            "documentTypeId" => "1"
        ];
        $controller = new DocumentFieldController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test post document field
     */
    public function test_post_document_field_creates_document_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_field_table.xml");
        $new_data = [
            "name" => "derp",
            "description" => "pred",
            "sequence" => "1",
            "mandatory" => "1",
            "documentTypeId" => "1"
        ];
        $controller = new DocumentFieldController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /*
     * Test change sequence
     */
    public function test_change_sequence()
    {
        $this->mySetup(__DIR__ . "/different_sequence_document_field_table.xml");
        $controller = new DocumentFieldController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();

        $json = json_decode($response->getBody(), true);
        $sequence_element_2 = $json['documentFields'][0]['sequence'];
        $sequence_element_1 = $json['documentFields'][1]['sequence'];

        self::assertEquals(1, $sequence_element_2, 'Document field 2, first element');
        self::assertEquals(2, $sequence_element_1, 'Document field 1, second element');
    }

}
