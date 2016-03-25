<?php
require_once "ApplicationError.php";
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
        print_r($exception);
        $this->title = "DB error: ";
        /*if (is_a($e, 'PDOException')) {
            switch ($e->getCode()) {
                case 23000:
                    $this->title .= "integrity constraint violation";
                    $this->message = $e;
                    // TODO handle DB error messages
                    /*
                    switch ($e->getCode()) {

                        case 1452:
                            $this->message =  "foreign key failed";
                            break;
                        default:
                            break;

                    };

                    break;
                default:
                    $this->message = $e;
                    break;

            }
        } else {
            $this->title .= "other error";
            $this->message = $e;
        }*/
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

    public function getStatusCode()
    {
        return 400;
    }


}