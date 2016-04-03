<?php


require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/target-groups/controllers/TargetGroupController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/NotFoundError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";

class TargetGroupControllerTest extends EHelseDatabaseTestCase
{
    public function test_get_all_topics_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController([], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        self::assertTrue(get_class($response) == Response::class);
        self::assertTrue($response->getResponseCode() == Response::STATUS_CODE_OK);
        self::assertTrue($this->isValidJSONTargetGroupList($response->getBody()));
    }

    public function test_get_topic_on_empty_table_returns_not_found_error()
    {
        $this->mySetup(__DIR__ . "/empty_target_group_table.xml");
        $controller = new TargetGroupController(["1"], Response::REQUEST_METHOD_GET,array());
        $response = $controller->getResponse();

        self::assertTrue(get_class($response) == ErrorResponse::class);
        self::assertTrue($response->getResponseCode() == Response::STATUS_CODE_NOT_FOUND);
        self::assertTrue($this->validateJSONErrorResponse($response->getBody()));
    }

    protected function isValidJSONTargetGroupList($body)
    {
        $json =  json_decode($body, true);
        $valid = true;
        try{
            $target_groups = $json['targetGroups'];
            if(is_array($target_groups)){
                foreach( $target_groups as $target_group){
                    if( !$this->validateJSONTargetGroup($target_group)){
                        $valid = false;
                        break;
                    }
                }
            }
            else{
                $valid = false;
            }
        }
        catch(Exception $e){
            $valid = false;
        }

        return $valid;
    }

    private function validateJSONTopic($topic)
    {
        if(is_string($topic)){
            $topic = json_decode($topic, true);
        }
        $valid = true;
        try{
            $topic['id'];
            $topic['timestamp'];
            $topic['title'];
            $topic['description'];
            $topic['parent'];
            $topic['isInCatalog'];
            $topic['sequence'];
            $topic['children'];
            $topic['documents'];
        }
        catch(Exception $e){
            $valid = false;
        }
        return $valid;
    }

    protected function validateJSONErrorResponse($error)
    {
        $json =  json_decode($error, true);
        $valid = true;
        try{
            $json['title'];
            $json['message'];
        }
        catch(Exception $e){
            $valid = false;
        }
        return $valid;
    }

}