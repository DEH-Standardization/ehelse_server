<?php

require_once 'ApplicationError.php';

class DuplicateUserError extends ApplicationError
{
    public function __construct()
    {
        $this->title = "Error: Duplicate user";
        $this->message = "A user is already registered with this information.";
        $this->response_code = Response::STATUS_CODE_CONFLICT;

    }

}