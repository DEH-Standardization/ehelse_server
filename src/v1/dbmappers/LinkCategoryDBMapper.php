<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/LinkCategory.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkCategoryDBMapper extends DBMapper
{

    /**
     * LinkCategoryDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'LinkCategory';
    }

}