<?php

require_once 'iController.php';
require_once 'standards/controllers/StandardController.php';
require_once 'profiles/controllers/ProfileController.php';
require_once 'topics/controllers/TopicController.php';
require_once 'login/controllers/LoginController.php';

class APIV1Controller implements iController
{
    private $controller;

    public function __construct($path, $method, $body)
    {
        $part = $path[0];

        //remove part from path array and fix index of array
        unset($path[0]);
        $path = array_values($path);

        switch($part)
        {
            case 'standards':
                $this->controller = new StandardController($path, $method, $body);
                break;
            case 'profiles':
                $this->controller = new ProfileController($path, $method, $body);
                break;
            case 'topics':
                $this->controller = new TopicController($path, $method, $body);
                break;
            case 'login':
                $this->controller = new LoginController($path, $method, $body);
                break;
            default:
                //TODO handle error
                $this->controller = new ErrorResponse(new InvalidPathError());
                break;
        }
    }

    public function getResponse()
    {
        return $this->controller->getResponse();
    }
}