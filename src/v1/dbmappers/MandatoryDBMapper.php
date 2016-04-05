<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Mandatory.php';
require_once __DIR__.'/../errors/DBError.php';

class MandatoryDBMapper extends DBMapper
{

    public function __construct()
    {
        parent::__construct();
        $this->model = 'Mandatory';
    }
}