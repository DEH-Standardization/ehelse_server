<?php
require_once __DIR__ . '/../../Response.php';
require_once __DIR__ . '/../../ResponseController.php';
require_once __DIR__ . '/../../InvalidPathError.php';
require_once __DIR__ . '/../../ErrorController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';


class TopicController extends ResponseController
{
    /**
     * TopicController constructor.
     * @param $path array Array containing the remaining path after v1/topics/ has been removed
     * @param $method string HTTP Request method
     * @param $body array Array containing request body
     */
    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        if(count($this->path) != 0){
            if(count($this->path) == 1 && is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }
    }

    /**
     * Function retrieving all topics in a structure representing the parent - child structure.
     * @return Response
     */
    protected function getAll()
    {
        return new Response("all topics");
    }

    /**
     * Function creating a new topic.
     * @return Response
     */
    protected function create()
    {
        return new Response("create topic");
    }

    /**
     * Function retrieving a topic with it's documents.
     * @return Response
     */
    protected function get()
    {
        return new Response("get topic");
    }

    /**
     * Function updating a topics values.
     * @return Response
     */
    protected function update()
    {
        return new Response("update topic");
    }


    /**
     * Function deleting a topic.
     * @return Response
     */
    protected function delete()
    {
        return new Response("delete topic");
    }
}
