<?php

require_once 'DBMapper.php';
require_once __DIR__ . '/../models/DocumentField.php';
require_once __DIR__ . '/../errors/DBError.php';
require_once __DIR__ . '/../models/DocumentFieldValue.php';

class DocumentFieldDBMapper extends DBMapper
{
    /**
     * DocumentFieldDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentField';
    }

}