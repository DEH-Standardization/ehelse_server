<?php

/**
 */
class Error
{
    protected $title, $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function __toString()
    {
        return $this->title . "<br>".$this->message;
    }



}