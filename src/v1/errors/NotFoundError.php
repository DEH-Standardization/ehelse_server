<?php

require_once 'ApplicationError.php';

class NotFoundError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_NOT_FOUND;

    /**
     * Constructor for method not allowed errors.
     * @param $method String with request method not allowed for current path
     */
    public function __construct()
    {
        $this->title = "Error: Not Found";
        $this->message = "Resource {$_SERVER['PATH_INFO']} not found";
    }
}