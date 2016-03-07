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
        /*
        $mapper = new DBMapper();
        $response = $mapper->getAllIds();
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $result = array('standards' => array());
        foreach ($response as $standard) {
            array_push($result['standards'], $standard->toArray());
        }
        return new Response(json_encode($result, JSON_PRETTY_PRINT));

         */
        return  new Response("get all std fields");
    }

    protected function get()
    {
        /*
        $mapper = new DocumentFieldDBMapper();
        $response = $mapper->getById($this->id);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return new Response($response->toJSON());
        */
        return  new Response("get specific std field");
    }

    protected function update()
    {
        /*
        $mapper = new StandardDBMapper();
        $assoc = $this->body;
        $standard = new Standard(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['topic_id']);
        $response = $mapper->update($standard);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
         */
        return  new Response("update std field");
    }

    protected function delete()
    {
        //TODO DELETE FIELD
        return  new Response("delete std field");
    }
}