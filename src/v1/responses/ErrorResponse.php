<?php
require_once "Response.php";

class ErrorResponse extends Response
{
    protected $content_type = Response::CONTENT_TYPE_JSON;
    protected $error;

    /**
     * ErrorResponse constructor.
     * @param $error
     */
    public function __construct($error)
    {
        $this->error = $error;
        $this->body = $error->toJSON();
        $this->response_code = $error->getResponseCode();
    }

    /**
     * Returns error
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}