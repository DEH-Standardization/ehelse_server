<?php

require_once "iController.php";
abstract class ResponseController implements iController
{
    protected  $method, $body, $path, $controller, $id;

    abstract protected function getAll();
    abstract protected function create();

    abstract protected function get();
    abstract protected function update();
    abstract protected function delete();

    //if $parts == 1,then remove std_id, if $parts == 2, then remove std_id AND 'verions'
    protected function trimPath($parts){
        if($parts == 1){
            //remove part from path array and fix index of array
            unset($this->path[0]);
            $this->path = array_values($this->path);

        }elseif($parts == 2){
            //remove part from path array and fix index of array
            unset($this->path[0]);
            unset($this->path[1]);
            $this->path = array_values($this->path);
        }
    }

    protected function handleRequest()
    {
        $response = "bla";
        switch($this->method){
            case "GET":
                $response = $this->get();
                break;
            case "POST":
                $response = "Error not suposed to post here";
                break;
            case "PUT":
                $response = $this->update();
                break;
            case "DELETE":
                $response = $this->delete();
                break;
        }
        return $response;
    }

    public function getResponse()
    {
        $response = null;

        if(!is_null($this->controller)){
            $response = $this->controller->getResponse();

        }elseif(is_null($this->controller) && !is_null($this->id)){
            $response = $this->handleRequest();

        }else{
            if($this->method == 'POST'){
                $response = $this->create();
            }elseif($this->method == 'GET'){
                $response = $this->getAll();
            }
        }
        return $response;
    }
}