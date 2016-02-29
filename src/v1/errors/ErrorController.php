<?php
require_once __DIR__."/../iController.php";
require_once __DIR__."/../responses/ErrorResponse.php";

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