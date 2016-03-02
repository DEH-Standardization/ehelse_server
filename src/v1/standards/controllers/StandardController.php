<?php
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../main/controllers/DescriptionController.php';
require_once __DIR__.'/../../dbmappers/StandardDBMapper.php';
require_once 'StandardVersionController.php';
require_once 'StandardFieldController.php';
require_once __DIR__.'/../../models/Standard.php';
require_once __DIR__.'/../../errors/DBError.php';

class StandardController extends ResponseController
{
    /*
     * Possibilities:
     *
     * .../standards
     * --method GET/POST
     *
     * .../standards/<id>
     * --return specific std
     *
     * .../standards/<id>/versions/...
     * --continue to version controller
     */


    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->trimPath(1);
            $this->controller = new StandardFieldController($this->path,$this->method,$this->body);
        }
        elseif(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];

                if($path[1] == 'versions'){
                    //send to StandardVersionController
                    $this->trimPath(2);
                    $this->controller = new StandardVersionController($this->path,$this->method,$this->body,$this->id);
                }else{
                    $this->controller = new DescriptionController();//return error
                }
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
        var_dump($body);
    }

    /**
     * Returns standard based on id
     * @return ErrorResponse|Response
     */
    protected function get()
    {
        $mapper = new StandardDBMapper();
        $response = $mapper->getStandardById($this->id);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return new Response($response->toJSON());
    }

    /**
     * Updates standard
     * @return ErrorResponse|Response
     */
    protected function update()
    {
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
    }

    protected function delete()
    {
        // TODO must find a solution to deleting
        return  new Response("Sd deleted");
    }

    /**
     * Returns all standards
     * @return ErrorResponse|Response
     */
    protected function getAll()
    {
        $mapper = new StandardDBMapper();
        $response = $mapper->getAll();
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $result = "";
        foreach ($response as $standard) {
            $result .= $standard->toJSON();
        }
        return new Response($result);
    }

    /**
     * Creates new standard
     * @return ErrorResponse|Response
     */
    protected function create()
    {
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
        $response = $mapper->add($standard);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
    }
}