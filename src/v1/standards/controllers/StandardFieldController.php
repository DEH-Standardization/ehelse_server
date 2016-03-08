<?php


class StandardFieldController extends ResponseController
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

    protected function create()
    {
        //TODO 10.03.2016

        /*
        $mapper = new DocumentFieldDBMapper();
        $assoc = $this->body;
        $document_field = new DocumentField(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['topic_id']);
        $response = $mapper->add($document_field);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
         */
        return  new Response("new std field");
    }

    protected function getAll()
    {

        //$mapper = new DocumentFieldDBMapper();
        //$response = $mapper->getAllIds();
        //if ($response instanceof DBError) {
        //    return new ErrorResponse($response);
        //}
        //$result = array('documentField' => array());
        //foreach ($response as $DocumentField) {
        //    array_push($result['documentFields'], $DocumentField->toArray());
        //}
        //return new Response(json_encode($result, JSON_PRETTY_PRINT));


        return  new Response("get all std fields");
    }

    protected function get()
    {
        $mapper = new DocumentFieldDBMapper();
        $response = $mapper->getById($this->id);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return new Response($response->toJSON());
    }

    protected function update()
    {
        $mapper = new StandardDBMapper();
        $assoc = $this->body;
        $document_field = new DocumentField(
            $assoc['id'],
            $assoc['name'],
            $assoc['description'],
            $assoc['sequence'],
            $assoc['mandatory'],
            $assoc['isStandardField'],
            $assoc['isProfileField']);
        $response = $mapper->update($document_field);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
    }

    protected function delete()
    {
        //TODO DELETE FIELD
        return  new Response("delete std field");
    }
}