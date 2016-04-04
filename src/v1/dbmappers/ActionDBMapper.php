<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Action.php';
require_once __DIR__.'/../errors/DBError.php';

class ActionDBMapper extends DBMapper
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Action';
    }

    public function get($action)
    {
        $this->getById($action->getId());
    }


    public function delete($model)
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented error");
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        throw new Exception("Not implemented error");
    }
}