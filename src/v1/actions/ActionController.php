<?php


require_once __DIR__ . '/../models/Action.php';
require_once __DIR__ . '/../responses/Response.php';
require_once __DIR__ . '/../responses/ErrorResponse.php';
require_once __DIR__ . '/../dbmappers/ActionDBMapper.php';
require_once __DIR__ . '/../responses/ResponseController.php';

class ActionController extends ResponseController
{

    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'ActionDBMapper';
        $this->list_name = 'actions';
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