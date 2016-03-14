<?php
require_once 'ApplicationError.php';

class InvalidJSONError extends ApplicationError
{

    /**
     * Error constructor.
     * @param $method
     */
    protected $status_code = Response::STATUS_CODE_BAD_REQUEST;

    public function __construct($body)
    {
        $this->title = "Error: Invalid JSON";
        $this->message = "Invalid JSON: {$body}";
    }

}
