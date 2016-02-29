<?php

/**
 * Error massage
 */
class Error
{
    protected $title, $message;

    /**
     * Error constructor.
     * @param $title
     * @param $message
     */
    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->title . "<br>".$this->message;
    }



}