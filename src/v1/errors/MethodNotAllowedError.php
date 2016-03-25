<?php
require_once 'ApplicationError.php';

class MethodNotAllowedError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_METHOD_NOT_ALLOWED;

    /**
     * Constructor for method not allowed errors.
     * @param $method String with request method not allowed for current path
     */
    public function __construct($method)
    {
        $this->title = "Error: Method not allowed";
        $this->message = "Method <{$method}> not allowed at " . $_SERVER['PATH_INFO'];
    }
}
