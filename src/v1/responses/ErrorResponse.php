<?php
require_once "Response.php";

class ErrorResponse extends Response
{
    protected $content_type = Response::CONTENT_TYPE_JSON;
    protected $error;

    public function __construct($error)
    {
        $this->body = $error->toJSON();
        $this->response_code = $error->getResponseCode();
    }
}