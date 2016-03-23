<?php

class UserController extends ResponseController
{

    public function __construct($path, $method, $body)
    {
        $this->body = $body;
        $this->method=$method;
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