<?php

require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/topics/controllers/TopicController.php";


class TopicControllerTest extends EHelseTestCase
{


    public function testGetResponse__path_empty__method_get__body_empty__returns_standards()
    {
        $controller = new TopicController($path = [], $method = Response::REQUEST_METHOD_GET, $body = "");
        $response = $controller->getResponse();

        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
        $this->assertEquals(Response::STATUS_CODE_OK, $response->getResponseCode());
        $json=json_decode($response->getBody(), true);
        $this->assertTrue($this->validateJSONTopicList($json));
    }
    public function testGetResponse__path_empty__method_put__body_empty__returns_method_not_allowed()
    {
        $controller = new TopicController($path = [], $method = Response::REQUEST_METHOD_PUT, $body = "");
        $response = $controller->getResponse();

        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
        $this->assertEquals(Response::STATUS_CODE_NOT_FOUND, $response->getResponseCode());
        $r = new MethodNotAllowedError(Response::REQUEST_METHOD_PUT);
        $this->assertEquals($response->getBody(), $r->toJSON());
    }

    private function validateJSONTopicList($json)
    {
        $valid = true;
        $topics = $json['topics'];
        if(is_array($topics)){
            foreach( $topics as $topic){
                if( !$this->validateJSONTopic($topic)){
                    $valid = false;
                    break;
                }
            }
        }
        else{
            $valid = false;
        }

        return $valid;
    }

    private function validateJSONTopic($topic)
    {
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

}
