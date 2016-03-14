<?php

require_once 'ApplicationError.php';

class MalformedJSONFormatError extends ApplicationError
{

    public function __construct($missing_fields)
    {
        $this->title = "Error: Invalid JSON";
        $missing_fields_string = implode(', ', $missing_fields);
        $this->message = "Missing Fields: {$missing_fields_string}";
        $this->response_code = Response::STATUS_CODE_BAD_REQUEST;
    }

}