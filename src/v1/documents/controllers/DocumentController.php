<?php

require_once  __DIR__ . '/../../responses/ResponseController.php';
require_once __DIR__ . '/../../models/Document.php';
require_once __DIR__ . '/../../errors/MalformedJSONFormatError.php';
require_once __DIR__ . '/../../responses/ErrorResponse.php';

class DocumentController extends ResponseController
{
    private $document_type;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;

        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->controller = new DocumentFieldController(array_shift($path),$this->method,$this->body);
        }
        elseif(count($path) >= 1){
            if(is_numeric($path[0])){
                $this->id = $path[0];
                $path = trimPath($path, 1);
                //TODO document field controller
            }else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }
    }

    protected function getAll()
    {
        $mapper = new DocumentDBMapper();
        $documents = $mapper->getAll();
        $topics_array = [];
        foreach($documents as $document){
            array_push($topics_array, $document->toArray());
        }

        $json = json_encode(array( "topics" => $topics_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    protected function create()
    {
        // TODO: Implement create() method.
        $missing_fields = ResponseController::validateJSONFormat($this->body,Document::REQUIRED_POST_FIELDS);
        if( $missing_fields ){
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }

        return $response;
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