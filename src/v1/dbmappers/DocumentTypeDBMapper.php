<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/DocumentType.php";
require_once __DIR__. "/../errors/DBError.php";


class DocumentTypeDBMapper extends DBMapper
{

    /**
     * DocumentTypeDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentType';
    }

    
}