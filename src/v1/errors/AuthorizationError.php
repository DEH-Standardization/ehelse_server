<?php


class AuthorizationError extends ApplicationError
{
    protected $response_code = Response::STATUS_CODE_UNAUTHORIZED,
        $path = "Undefined";

    /**
     * AuthorizationError constructor.
     * @param $method
     */
    public function __construct($method)
    {
        $this->title = "Error: Authorization Error";
        if (array_key_exists('PATH_INFO', $_SERVER)) {
            $this->path = $_SERVER['PATH_INFO'];
        }
        $this->message = "You are not authorized at <{$this->path}> with method <$method>";
    }

}