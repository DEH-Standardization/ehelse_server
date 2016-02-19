<?php

    /*
     * Possibilities:
     *
     * .../versions
     * --method GET/POST
     *
     * .../versions/<id>
     * --return specific version
     */
class StandardVersionController implements iController
{
    private $method, $body, $path, $standard_id;

    public function __construct($path, $method, $body, $standard_id)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;
        $this->standard_id = $standard_id;
    }

    public function getResponse()
    {
        $response = null;

        echo print_r($this->path);
        if(empty($this->path)){
            //if empty array, then list all versions OR create new
            if($this->method == 'GET'){
                $response = 'list all std versions';
            }elseif($this->method == 'POST'){
                $response = 'create new std version';
            }
        }elseif(is_numeric($this->path[0])){
            //if not empty, check if number
            $response = 'list specific version';
        }else{
            //else return error
            $response = new DescriptionController();
        }
        return $response;
    }
}