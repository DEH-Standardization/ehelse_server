<?php
require_once __DIR__ . "/../iController.php";
require_once __DIR__ . "/../responses/ErrorResponse.php";

class ErrorController implements iController
{
    protected $error;

    /**
     * ErrorController constructor.
     * @param $error
     */
    public function __construct($error)
    {
        $this->error = $error;
    }

    /**
     * Returns response
     * @return ErrorResponse
     */
    public function getResponse()
    {
        return new ErrorResponse($this->error);
    }
}