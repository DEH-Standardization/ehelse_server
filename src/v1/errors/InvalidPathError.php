<?php

class InvalidPathError
{
    protected $title, $message;

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
        ), JSON_PRETTY_PRINT);
    }

}
