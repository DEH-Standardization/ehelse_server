<?php

require_once 'ApplicationError.php';

class NotFoundError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_NOT_FOUND,
        $path = "Undefined";

    /**
     * Constructor for method not allowed errors.
     * @param $method String with request method not allowed for current path
     */
    public function __construct()
    {
        $this->title = "Error: Not Found";
        if(array_key_exists('PATH_INFO', $_SERVER)){
            $this->path = $_SERVER['PATH_INFO'];
        }
        $this->message = "Resource <{$this->path}> not found";
    }
}