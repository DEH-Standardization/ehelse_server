<?php

require_once 'ApplicationError.php';

class MalformedJSONFormatError extends ApplicationError
{

    /**
     * Error constructor.
     * @param $method
     */
    protected $status_code = Response::STATUS_CODE_BAD_REQUEST;

    public function __construct($missing_fields)
    {
        $this->title = "Error: Invalid JSON";
        $missing_fiels_string = implode(', ', $missing_fields);
        $this->message = "Missing Fields: {$missing_fiels_string}";
    }

}