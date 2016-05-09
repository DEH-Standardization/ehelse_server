<?php

require_once 'DBMapper.php';
require_once 'DBCommunication.php';
require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../errors/DBError.php';

class StatusDBMapper extends DBMapper
{

    /**
     * StatusDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Status';
    }


}