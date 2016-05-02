<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Action.php';
require_once __DIR__.'/../errors/DBError.php';

class ActionDBMapper extends DBMapper
{
    /**
     * ActionDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Action';
    }

}