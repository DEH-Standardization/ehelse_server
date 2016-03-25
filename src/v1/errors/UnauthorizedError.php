<?php
require_once 'ApplicationError.php';

class UnauthorizedError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_UNAUTHORIZED;

    public function __construct($method)
    {
        $this->title = "Error: Unauthorized access";
        $this->message = "Unauthorized at <{$_SERVER['PATH_INFO']}> with method <$method>";
    }
}