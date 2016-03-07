<?php

/**
 * ApplicationError massage
 */
class ApplicationError
{
    protected $title, $message, $status_code;

    /**
     * ApplicationError constructor.
     * @param $title
     * @param $message
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
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