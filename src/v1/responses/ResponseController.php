<?php
require_once __DIR__."/../iController.php";
require_once __DIR__."/../../v1/errors/MethodNotAllowedError.php";
require_once __DIR__."/ErrorResponse.php";

abstract class ResponseController implements iController
{
    protected  $method, $body, $path, $controller, $id, $db_mapper, $list_name;

    abstract protected function getAll();

    protected function getAllHelper()
    {
        $mapper = new $this->db_mapper();
        $objects = $mapper->getAll();
        $object_array = [];
        foreach($objects as $object){
            array_push($object_array, $object->toArray());
        }

        $json = json_encode(array( $this->list_name => $object_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    abstract protected function create();

    protected function getOptions(){
        return new Response("{}");
    }

    protected function get()
    {
        $mapper = new $this->db_mapper();
        $object = $mapper->getById($this->id);
        if($object){
            $response = new Response(json_encode($object->toArray(), JSON_PRETTY_PRINT));
        }
        else{
            $response = new ErrorResponse(new NotFoundError());
        }
        return $response;
    }
    abstract protected function update();
    abstract protected function delete();


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

    protected function init($path, $method, $body)
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

    protected static function validateJSONFormat($json, $required_fields)
    {
        $missing_fields = [];
        foreach($required_fields as $required_field){
            if( !array_key_exists($required_field, $json)){
                array_push($missing_fields, $required_field);
            }
        }
        return $missing_fields;
    }
}