<?php

require_once __DIR__."/../../dbmappers/DocumentFieldDBMapper.php";

class DocumentFieldController extends ResponseController
{

    public function __construct($path, $method, $body)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;

        if(count($this->path) > 0){
            if(is_numeric($this->path[0])){
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
        $document_field_db_mapper = new DocumentFieldDBMapper();
        return new Response(json_encode($document_field_db_mapper->deleteById($this->id),JSON_PRETTY_PRINT));
    }
}