<?php

require_once 'v1/iController.php';
require_once 'v1/APIV1Controller.php';
require_once 'v1/main/controllers/DescriptionController.php';

class MainController implements iController{
    private $controller, $api_version, $path;

    public function __construct($path, $method, $body)
    {
        $this->path = $path;

        print_r($path);

        $this->api_version = $path[0];

        //check if the url ended with '/', if se delete
        if(end($path) == ''){
            $ak = array_keys($path);
            unset($path[end($ak)]);
        }
        $path = trimPath($path, 1);

        switch($this->api_version)
        {
            case 'v1':
                $this->controller = new APIV1Controller($path, $method, $body);
                break;
            default:
                //TODO handle error
                $this->controller = new DescriptionController();
                break;
        }
    }

    public function getResponse(){
        return $this->controller->getResponse();
    }
}