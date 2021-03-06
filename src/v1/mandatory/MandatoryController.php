<?php


require_once __DIR__ . '/../models/Mandatory.php';
require_once __DIR__ . '/../responses/Response.php';
require_once __DIR__ . '/../responses/ErrorResponse.php';
require_once __DIR__ . '/../dbmappers/MandatoryDBMapper.php';
require_once __DIR__ . '/../responses/ResponseController.php';

class MandatoryController extends ResponseController
{

    /**
     * MandatoryController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'MandatoryDBMapper';
        $this->list_name = 'mandatory';
        $this->model = 'Mandatory';
    }
}