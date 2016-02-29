<?php

class ProfileVersionController implements iController
{
    private $method, $body, $path, $profile_id;

    public function __construct($path, $method, $body, $profile_id)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;
        $this->profile_id = $profile_id;
    }

    public function getResponse()
    {
        $response = null;

        echo print_r($this->path);
        if(empty($this->path)){
            //if empty array, then list all versions OR create new
            if($this->method == 'GET'){
                $response = 'list all profile versions';
            }elseif($this->method == 'POST'){
                $response = 'create new profile version';
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