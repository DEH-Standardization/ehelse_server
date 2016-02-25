<?php

require_once __DIR__ . "/../../ResponseController.php";

class StandardVersionController extends ResponseController
{

    public function __construct($path, $method, $body, $standard_id)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;

        if(empty($this->path)){

        }elseif(is_numeric($this->path[0])){
            $this->id = $this->path[0];
        }else{
            $this->controller = new DescriptionController();
        }
    }


    protected function create()
    {
        return "create std version";
    }

    protected function getAll()
    {
        return "get std versions";
    }

    protected function get()
    {
        return "get std version";
    }

    protected function update()
    {
        return "update std version";
    }

    protected function delete()
    {
        return "delete std version";
    }
}