<?php

require_once __DIR__ . '/../../standards/controllers/StandardController.php';
require_once __DIR__ . '/../../profiles/controllers/ProfileController.php';
require_once __DIR__ . '/../../iController.php';
require_once 'APIV1Controller.php';
require_once 'DescriptionController.php';

class MainController implements iController{
    private $controller, $api_version, $path;

    public function __construct($api_version, $path, $method, $body)
    {
        $this->path = $path;
        $path = explode('/',$this->path);

        $this->api_version = $path[1];

        //check if the url ended with '/', if se delete
        if(end($path) == ''){
            $ak = array_keys($path);
            unset($path[end($ak)]);
        }
        unset($path[0]); //remove blank from explode
        unset($path[1]); //remove version
        $path = array_values($path); //remaining path

        if(count($path) == 0){
            $this->api_version = ' ';
        }

        switch($this->api_version)
        {
            case 'v1':
                //send to standard section
                $this->controller = new APIV1Controller($path, $method, $body);
                break;
            default:
                //Handle error
                //TODO handle error
                $this->controller = new DescriptionController();
                break;
        }
    }

    public function getResponse(){
        return $this->controller->getResponse();
    }
}