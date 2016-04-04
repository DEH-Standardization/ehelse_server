<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../errors/DBError.php';

class StatusDBMapper extends DBMapper
{
    private $table_name = 'status';

    public function __construct()
    {
        parent::__construct();
        $this->model = 'Status';
    }


}