<?php
require_once "Error.php";
/**
 */
class DBError extends Error
{

    public function __construct($e)
    {
        $this->title = "DB error: ";
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
                break;

        }
    }

}