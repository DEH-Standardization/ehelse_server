<?php
require_once 'Error.php';

class InvalidPathError extends Error
{

    /**
     * Error constructor.
     * @param $method
     */
    protected $status_code = Response::STATUS_CODE_NOT_FOUND;

    public function __construct()
    {
        $this->title = "Error: Invalid path";
        $this->message = "Invalid path <{$_SERVER['PATH_INFO']}>";
    }

}
