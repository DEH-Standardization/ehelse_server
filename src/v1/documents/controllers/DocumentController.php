<?php

require_once  __DIR__ . '/../../responses/ResponseController.php';

class DocumentController extends ResponseController
{
    private $document_type;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;

        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->trimPath(1);
            $this->controller = new DocumentFieldController($this->path,$this->method,$this->body);
        }
        elseif(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new ErrorController(new InvalidPathError());//return error
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new ErrorController(new InvalidPathError());//return error
            }
        }
    }

    protected function getAll()
    {
        // TODO: Implement getAll() method.
    }

    protected function create()
    {
        // TODO: Implement create() method.
    }

    protected function get()
    {
        // TODO: Implement get() method.
    }

    protected function update()
    {
        // TODO: Implement update() method.
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
    }
}