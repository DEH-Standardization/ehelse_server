<?php

require_once 'ApplicationError.php';
require_once __DIR__.'/../responses/Response.php';

/**
 * Error message for database errors
 */
class DBError extends ApplicationError
{

    /**
     * DBError constructor.
     * @param $e
     */
    public function __construct($exception)
    {

        $this->title = "Database Error";
        $this->message = $exception->getMessage();
        $this->response_code = Response::STATUS_CODE_INTERNAL_ERROR;
    }

    public function toJSON()
    {
        return json_encode(array(
            "title" => $this->title,
            "message" => $this->message
        ), JSON_PRETTY_PRINT);
    }




}