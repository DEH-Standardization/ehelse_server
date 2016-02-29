<?php

class MethodNotAllowedError
{
    protected $status_code = Response::STATUS_CODE_METHOD_NOT_ALLOWED;

    protected $title, $message;

    /**
     * Constructor for method not allowed errors.
     * @param $method String with request method not allowed for current path
     */
    public function __construct($method)
    {
        $this->title = "Error: Method not allowed";
        $this->message = "Method <{$method}> not allowed at " . $_SERVER['PATH_INFO'];
    }

    /**
     * @return integer response status code
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title . "<br>".$this->message;
    }

    public function toJSON()
    {
        return json_encode(array(
            "title" => $this->title,
            "message" => $this->message
        ));
    }

}
