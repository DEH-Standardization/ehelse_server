<?php

require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/topics/controllers/TopicController.php";


class TopicControllerTest extends EHelseTestCase
{

    //GET no id no body
    public function testGetResponse__path_empty__method_get__body_empty__returns_topics()
    {
        $controller = new TopicController($path = [], $method = Response::REQUEST_METHOD_GET, $body = "");
        $response = $controller->getResponse();

        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
        $this->assertEquals(Response::STATUS_CODE_OK, $response->getResponseCode());
        $json=json_decode($response->getBody(), true);
        $this->assertTrue($this->validateJSONTopicList($json));
    }

    //GET with id no body
    public function testPostResponse__path_id__method_get__body_empty__returns_topic()
    {
        $controller = new TopicController($path = [1], $method = Response::REQUEST_METHOD_GET, $body = "");
        $response = $controller->getResponse();

        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
        $this->assertEquals(Response::STATUS_CODE_OK, $response->getResponseCode());
        $json=json_decode($response->getBody(), true);
        $this->assertTrue($this->validateJSONTopic($json));
    }

    //POST no id JSON in body
    public function testPostResponse__path_empty__method_post__body_json__returns_topic()
    {
        $topic = new Topic(null,null,"test","unit test",true,0,null);


        $controller = new TopicController($path = [], $method = Response::REQUEST_METHOD_POST, $body = $topic->toArray());
        $response = $controller->getResponse();

        $this->assertEquals(Response::CONTENT_TYPE_JSON, $response->getContentType());
        $this->assertEquals(Response::STATUS_CODE_OK, $response->getResponseCode());
        $json=json_decode($response->getBody(), true);
        $this->assertTrue($this->validateJSONTopic($json));
    }


    //PUT id JSON in body










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
