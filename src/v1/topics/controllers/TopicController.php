<?php
require_once __DIR__ . '/../../ResponseController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';

class TopicController extends ResponseController
{


    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;




        if(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
    }

    protected function getAll()
    {
        return "all topics";
    }

    protected function create()
    {
        return "create topic";
    }

    protected function get()
    {
        return "get topic";
    }

    protected function update()
    {
        return "update topic";
    }

    protected function delete()
    {
        return "delte topic";
    }
}