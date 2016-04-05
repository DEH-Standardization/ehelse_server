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
        $this->model = 'Action';
    }
}