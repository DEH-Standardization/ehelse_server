<?php

require_once __DIR__ . "/../../dbmappers/DocumentTypeDBMapper.php";

class DocumentTypeController extends ResponseController
{
    /**
     * DocumentTypeController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'DocumentTypeDBMapper';
        $this->list_name = 'documentTypes';
        $this->model = 'DocumentType';
    }

}