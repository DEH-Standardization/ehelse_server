<?php
require_once 'ApplicationError.php';

class InvalidPathError extends ApplicationError
{

    /**
     * Error constructor.
     * @param $method
     */
    protected $response_code = Response::STATUS_CODE_NOT_FOUND;

    public function __construct()
    {
        $this->title = "Error: Invalid path";
        $this->message = "Invalid path <{$_SERVER['PATH_INFO']}>";
    }

}
