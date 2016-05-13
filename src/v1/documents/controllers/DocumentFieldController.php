<?php

require_once __DIR__ . "/../../dbmappers/DocumentFieldDBMapper.php";

class DocumentFieldController extends ResponseController
{

    /**
     * DocumentFieldController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'DocumentFieldDBMapper';
        $this->list_name = 'documentFields';
        $this->model = 'DocumentField';
    }
}