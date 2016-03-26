<?php


class AuthorizationError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_UNAUTHORIZED;

    public function __construct($method)
    {
        $this->title = "Error: Authorization Error";
        $this->message = "You are not authorized at <{$_SERVER['PATH_INFO']}> with method <$method>";
    }

}