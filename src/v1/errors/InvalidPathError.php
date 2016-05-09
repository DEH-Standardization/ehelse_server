<?php
require_once 'ApplicationError.php';

class InvalidPathError extends ApplicationError
{

    protected $response_code = Response::STATUS_CODE_NOT_FOUND,
        $path = "Undefined";

    /**
     * InvalidPathError constructor.
     */
    public function __construct()
    {
        $this->title = "Error: Invalid path";
        if (array_key_exists('PATH_INFO', $_SERVER)) {
            $this->path = $_SERVER['PATH_INFO'];
        }
        $this->message = "Invalid path <{$this->path}>";
    }

}
