<?php
require_once 'Error.php';

class UnauthorizedError extends Error
{
    protected $status_code = Response::STATUS_CODE_UNAUTHORIZED;

    public function __construct($method)
    {
        $this->title = "Error: Unauthorized access";
        $this->message = "Unauthorized at <{$_SERVER['PATH_INFO']}> with method <$method>";
    }
}