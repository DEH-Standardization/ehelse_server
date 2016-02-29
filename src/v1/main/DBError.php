<?php
require_once "Error.php";
/**
 * Error message for database errors
 */
class DBError extends Error
{
    /**
     * DBError constructor.
     * @param $e
     */
    public function __construct($e)
    {
        $this->title = "DB error: ";
        if (is_a($e, 'PDOException')) {
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
                    */
                    break;
                default:
                    $this->message = $e;
                    break;

            }
        } else {
            $this->title .= "other error";
            $this->message = $e;
        }

    }


}