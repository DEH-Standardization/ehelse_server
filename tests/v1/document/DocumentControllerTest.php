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

    public function test_delete_document_returns()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $controller = new DocumentController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        self::assertIsValidDeleteResponse($response);
    }

    public function test_update_document_updated_document()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [],
            "targetGroups" => []

        ];
        $controller = new DocumentController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    public function test_post_document_created_document()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [],
            "targetGroups" => []
        ];
        $controller = new DocumentController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }


    public function test_add_profile_to_existing_document_return_profile()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $standard_id = 1;
        $profile = [
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "2",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => $standard_id,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [],
            "targetGroups" => []
        ];
        $profile_controller = new DocumentController([], Response::REQUEST_METHOD_POST, $profile);
        $profile_response = $profile_controller->getResponse();
        self::assertIsValidResponse($profile_response);
        // Check that profile is successfully created, and correct response is returned
        self::assertIsCorrectResponseData($profile_response->getBody(), $profile);

        $profile_id = json_decode($profile_response->getBody(), true)['id'];
        $standard_controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
        $standard_response = $standard_controller->getResponse();
        self::assertIsValidResponse($standard_response);

        $profiles = json_decode($standard_response->getBody(), true)['profiles'];
        $flat_array_profiles = $this->getFlatArray($profiles);

        // Check if added profile is in the profile list of the standard
        self::assertTrue(in_array($profile_id, $flat_array_profiles));
    }

/*
    public function test_add_new_version_of_document_return_created_document()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_version = [
            "title" => "Nytt dokument",
            "description" => "Dette dokumentet er nytt",
            "sequence" => "4",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "112",
            "hisNumber" => "223",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => 1,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [],
            "targetGroups" => []
        ];
        $version_controller = new DocumentController([], Response::REQUEST_METHOD_POST, $new_version);
        $version_response = $version_controller->getResponse();
        self::assertIsValidResponse($version_response);
        // Check that new version is successfully created, and correct response is returned
        self::assertIsCorrectResponseData($version_response->getBody(), $new_version);

        $version_id = json_decode($version_response->getBody(), true)['id'];
        $standard_controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
        $standard_response = $standard_controller->getResponse();
        self::assertIsValidResponse($standard_response);

        $next_document_id = json_decode($standard_response->getBody(), true)['nextDocumentId'];

        // Check if new version is in the next document for the document
        self::assertEquals($version_id, $next_document_id);
    }
*/

    // This is not wokring
    public function test_delete_document_version_return_is_prev_id_null()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $prev_document_controller = new DocumentController([2], Response::REQUEST_METHOD_DELETE, array());
        $prev_document_response = $prev_document_controller->getResponse();
        self::assertIsValidDeleteResponse($prev_document_response);

        $document_controller = new DocumentController([3], Response::REQUEST_METHOD_GET, array());
        $document_response = $document_controller->getResponse();
        self::assertIsValidResponse($document_response);

        $previous_document_id = json_decode($document_response->getBody(), true)['previousDocumentId'];

        // Check if new version is in the next document for the document
        self::assertEquals(null, $previous_document_id);
    }


    public function test_change_sequence_document_version_return_()
    {
        // Basic setup
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $basic_controller = new DocumentController([], Response::REQUEST_METHOD_GET, array());
        $response = $basic_controller->getResponse();
        $json = json_decode($response->getBody(), true);
        $sequence_element_1 = $json['documents'][0]['sequence'];
        $sequence_element_2 = $json['documents'][1]['sequence'];
        self::assertEquals(1, $sequence_element_1, 'Topic 1, first element');
        self::assertEquals(2, $sequence_element_2, 'Topic 2, second element');

        // Different seduce, opposite order
        $this->mySetup(__DIR__ . "/different_sequence_document_table.xml");
        $different_sequence_controller = new DocumentController([], Response::REQUEST_METHOD_GET, array());
        $response = $different_sequence_controller->getResponse();
        $json = json_decode($response->getBody(), true);
        $sequence_element_2 = $json['documents'][0]['sequence'];
        $sequence_element_1 = $json['documents'][1]['sequence'];

        self::assertEquals(1, $sequence_element_2, 'Topic 2, first element');
        self::assertEquals(2, $sequence_element_1, 'Topic 1, second element');

    }


    public function test_add_field_to_document_return_document_with_new_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [
                [
                    "fieldId" => 1,
                    "value" => "aaa"
                ]
            ],
            "targetGroups" => []

        ];
        $controller = new DocumentController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);

    }

    public function test_update_field_to_document_return_document_with_new_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [
                [
                    "fieldId" => 1,
                    "value" => "bbbb"
                ]
            ],
            "targetGroups" => []

        ];
        $controller = new DocumentController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);

    }


    public function test_delete_links_fields_target_groups_for_document_return_document_without_links_fields_target_groups_field()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [],
            "fields" => [],
            "targetGroups" => []

        ];
        $controller = new DocumentController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);

    }


    public function test_delete_document_field_return_document_without_fields()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        // Delete
        $document_field_controller = new DocumentFieldController([1], Response::REQUEST_METHOD_DELETE, array());
        $document_field_response = $document_field_controller->getResponse();
        self::assertIsValidDeleteResponse($document_field_response);
        // Check document
        $document_controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
        $document_response = $document_controller->getResponse();
        self::assertIsValidResponse($document_response);

        $fields = json_decode($document_response->getBody(), true)['fields'];
        self::assertEquals([], $fields);
    }

    public function test_delete_links_category_return_document_without_links()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        // Delete
        $link_category_controller = new LinkCategoryController([1], Response::REQUEST_METHOD_DELETE, array());
        $document_field_response = $link_category_controller->getResponse();
        self::assertIsValidDeleteResponse($document_field_response);
        // Check document
        $document_controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
        $document_response = $document_controller->getResponse();
        self::assertIsValidResponse($document_response);

        $fields = json_decode($document_response->getBody(), true)['links'];
        self::assertEquals([], $fields);
    }

    public function test_target_group_category_return_document_without_target_groups()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        // Delete
        $target_group_controller = new TargetGroupController([1], Response::REQUEST_METHOD_DELETE, array());
        $document_field_response = $target_group_controller->getResponse();
        self::assertIsValidDeleteResponse($document_field_response);
        // Check document
        $document_controller = new DocumentController([1], Response::REQUEST_METHOD_GET, array());
        $document_response = $document_controller->getResponse();
        self::assertIsValidResponse($document_response);

        $fields = json_decode($document_response->getBody(), true)['targetGroups'];
        self::assertEquals([], $fields);
    }


    public function test_add_link_to_document_return_document_with_new_link()
    {
        $this->mySetup(__DIR__ . "/basic_document_table.xml");
        $new_data = [
            "id" => 1,
            "title" => "Oppdatert dokument",
            "description" => "Dette dokumentet er oppdatert",
            "sequence" => "3",
            "topicId" => "1",
            "statusId" => "1",
            "documentTypeId" => "1",
            "internalId" => "111",
            "hisNumber" => "222",
            "comment" => null,
            "standardId" => null,
            "previousDocumentId" => null,
            "nextDocumentId" => null,
            "links" => [
                [
                    'text' => "aaa",
                    'description' => "aaa",
                    'url' => "aaa",
                    'linkCategoryId' => 1
                ]
            ],
            "fields" => [],
            "targetGroups" => []

        ];
        $controller = new DocumentController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);
        print_r($response);
        self::assertIsCorrectResponseData($response->getBody(), $new_data);

    }

    /**
     * Returns a flat version of an array
     * @param $array
     * @return array
     */
    private function getFlatArray($array)
    {
        $flat_array = array();
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        foreach ($it as $item) {
            array_push($flat_array, $item);
        }
        return $flat_array;
    }


}
