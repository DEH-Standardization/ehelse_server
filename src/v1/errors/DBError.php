<?php
require_once "ApplicationError.php";
/**
 * Error message for database errors
 */
class DBError extends ApplicationError
{

    protected $response_code = Response::STATUS_CODE_INTERNAL_ERROR;
    /**
     * DBError constructor.
     * @param $e
     */
    public function __construct($exception)
    {

        $this->title = "Database Error";
        $this->message = $exception->getMessage();

    }

    public function toJSON()
    {
        return json_encode(array(
            "title" => $this->title,
            "message" => $this->message
        ), JSON_PRETTY_PRINT);
    }




}