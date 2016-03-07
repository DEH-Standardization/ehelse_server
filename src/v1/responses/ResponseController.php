<?php
require_once __DIR__."/../iController.php";
require_once __DIR__."/../../v1/errors/MethodNotAllowedError.php";
require_once __DIR__."/ErrorResponse.php";

abstract class ResponseController implements iController
{
    protected  $method, $body, $path, $controller, $id;

    abstract protected function getAll();
    abstract protected function create();

    protected function getOptions(){
        return new Response("{}");
    };

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
        $response = null;
        switch($this->method){
            case Response::REQUEST_METHOD_GET:
                $response = $this->get();
                break;
            case Response::REQUEST_METHOD_PUT:
                $response = $this->update();
                break;
            case Response::REQUEST_METHOD_DELETE:
                $response = $this->delete();
                break;
            case Response::REQUEST_METHOD_OPTIONS:
                $response = $this->getOptions();
                break;
            default:
                $response = new ErrorResponse(new MethodNotAllowedError(Response::REQUEST_METHOD_POST));
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
            if($this->method == Response::REQUEST_METHOD_POST){
                $response = $this->create();
            }elseif($this->method == Response::REQUEST_METHOD_GET){
                $response = $this->getAll();
            }elseif($this->method == Response::REQUEST_METHOD_OPTIONS){
                $response = $this->getOptions();
            }
            else{
                $response = new ErrorResponse(new MethodNotAllowedError($this->method));
            }
        }
        return $response;
    }
}