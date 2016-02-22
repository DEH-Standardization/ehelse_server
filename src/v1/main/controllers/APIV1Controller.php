<?php

require_once __DIR__ . '/../../iController.php';
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
            default:
                //TODO handle error
                $this->controller = new DescriptionController();
                break;
        }
    }

    public function getResponse()
    {
        return $this->controller->getResponse();
    }
}