<?php
require_once 'ApplicationError.php';

class AuthenticationError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_UNAUTHORIZED,
        $path = "Undefined";

    public function __construct($method)
    {
        $this->title = "Error: Authentication Error";
        if(array_key_exists('PATH_INFO', $_SERVER)){
            $this->path = $_SERVER['PATH_INFO'];
        }
        $this->message = "You must authenticate at <{$this->path}> with method <$method>";
    }
}