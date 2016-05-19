<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/documents/controllers/DocumentController.php";

class DocumentControllerTest extends EHelseDatabaseTestCase
{
    const list_name = "documents";
    const fields = ["id", "timestamp", "title", "description", "statusId", "sequence", "topicId", "comment",
        "documentTypeId", "standardId", "previousDocumentId", "nextDocumentId", "internalId", "hisNumber",
        "profiles", "links", "fields", "targetGroups"];


/*
    public function test_get_all_documents_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_document_table.xml");
        $controller = new DocumentController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }


    public function test_get_all_documents_on_basic_table_returns_statues()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $controller = new DocumentController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

        public function test_get_document_on_empty_table_returns_error()
        {
            $this->mySetup(__DIR__ . "/empty_document_table.xml");
            $controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
            $response = $controller->getResponse();
            self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
        }

        public function test_get_document_on_basic_table_returns_document()
        {
            $this->mySetup(__DIR__ . "/basic_document_table.xml");
            $controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
            $response = $controller->getResponse();
            self::assertIsValidResponse($response);
        }
    */
        public function test_delete_document_returns()
        {
            $this->mySetup(__DIR__ . "/basic_document_table.xml");
            $controller = new DocumentController([1], Response::REQUEST_METHOD_DELETE, array());
            $response = $controller->getResponse();
            self::assertIsValidDeleteResponse($response);
        }

    /*

            public function test_update_action_updated_action()
            {
                $this->mySetup(__DIR__ . "/basic_action_table.xml");
                $new_data = [
                    "id" => 1,
                    "name" => "123",
                    "description" => "321"
                ];
                $controller = new ActionController([1], Response::REQUEST_METHOD_PUT, $new_data);
                $response = $controller->getResponse();
                self::assertIsValidResponse($response);

                self::assertIsCorrectResponseData($response->getBody(), $new_data);
            }

            public function test_post_action_creates_action()
            {
                $this->mySetup(__DIR__ . "/basic_action_table.xml");
                $new_data = [
                    "name" => "derp",
                    "description" => "pred"
                ];
                $controller = new ActionController([], Response::REQUEST_METHOD_POST, $new_data);
                $response = $controller->getResponse();
                self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

                self::assertIsCorrectResponseData($response->getBody(), $new_data);
            }*/

}
