<?php
require_once 'ApplicationError.php';

class InvalidJSONError extends ApplicationError
{

    /**
     * Error constructor.
     * @param $method
     */
    public function __construct($body)
    {
        $this->title = "Error: Invalid JSON";
        $this->message = "Invalid JSON: {$body}";
        $this->response_code = Response::STATUS_CODE_BAD_REQUEST;
    }

}
