<?php
require_once __DIR__ . '/../../Response.php';
require_once __DIR__ . '/../../ResponseController.php';
require_once __DIR__ . '/../../InvalidPathError.php';
require_once __DIR__ . '/../../ErrorController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';

class TopicController extends ResponseController
{


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

    protected function getAll()
    {
        return new Response("all topics");
    }

    protected function create()
    {
        return new Response("create topic");
    }

    protected function get()
    {
        return new Response("get topic");
    }

    protected function update()
    {
        return new Response("update topic");
    }

    protected function delete()
    {
        return new Response("delte topic");
    }
}