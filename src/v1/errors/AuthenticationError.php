<?php
require_once 'ApplicationError.php';

class AuthenticationError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_UNAUTHORIZED;

    public function __construct($method)
    {
        $this->title = "Error: Authentication Error";
        $this->message = "You must authenticate at <{$_SERVER['PATH_INFO']}> with method <$method>";
    }
}