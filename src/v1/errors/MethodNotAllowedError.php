<?php
require_once 'Error.php';

class MethodNotAllowedError extends Error
{
    protected $status_code = Response::STATUS_CODE_METHOD_NOT_ALLOWED;

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
