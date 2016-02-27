<?php

class MethodNotAllowedError
{
    protected $title, $message;
    /**
     * Error constructor.
     * @param $method
     */
    protected $status_code = Response::STATUS_CODE_METHOD_NOT_ALLOWED;

    public function __construct($method)
    {
        $this->title = "Error: Method not allowed";
        $this->message = "Method <{$method}> not allowed at " . $_SERVER['PATH_INFO'];
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * toString
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
