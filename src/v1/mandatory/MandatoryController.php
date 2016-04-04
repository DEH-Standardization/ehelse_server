<?php


require_once __DIR__ . '/../models/Mandatory.php';
require_once __DIR__ . '/../responses/Response.php';
require_once __DIR__ . '/../responses/ErrorResponse.php';
require_once __DIR__ . '/../dbmappers/MandatoryDBMapper.php';
require_once __DIR__ . '/../responses/ResponseController.php';

class MandatoryController extends ResponseController
{


    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'MandatoryDBMapper';
        $this->list_name = 'mandatory';
    }

    protected function getAll()
    {
        return $this->getAllHelper();
    }

    protected function create()
    {
        // TODO: Implement create() method.
        throw new Exception("Not implemented error");
    }



    protected function update()
    {
        // TODO: Implement update() method.
        throw new Exception("Not implemented error");
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented error");
    }
}