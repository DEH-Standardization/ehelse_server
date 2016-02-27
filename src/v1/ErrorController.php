<?php


require_once "iController.php";
require_once "ErrorResponse.php";

class ErrorController implements iController
{
    protected $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function getResponse()
    {
        return new ErrorResponse($this->error);
    }
}