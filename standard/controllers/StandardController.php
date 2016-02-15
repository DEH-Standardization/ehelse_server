<?php

require_once __DIR__ . '/../../iController.php';

class StandardController implements iController
{
    private $method, $body, $path, $controller, $standard_id;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        echo print_r($path);

        if(count($path) == 1){
            //TODO Check if number, if not return error

            //TODO if number, set standard_id = that number

        }elseif(count($path) >= 2){
            //TODO if number, set standard_id = that number
            //TODO if part 2 == versions, then make new instance of standardversionController, and send in path - what just checked
            //   --> send in standard_id on that standard
        }
    }

    public function get_response(){
        //TODO you got controller? if so, use its get_response.

        //TODO if not controller, but standard_id, then return the json for THAT standard.

        //TODO if neither, return a list with all standards.


        return json_encode($this->body);
    }

}