<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/DocumentType.php";
require_once __DIR__. "/../errors/DBError.php";


class DocumentTypeDBMapper extends DBMapper
{

    private $table_name = 'document_type';

    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentType';
    }

    
}