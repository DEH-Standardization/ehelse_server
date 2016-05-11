<?php
require_once __DIR__ . '/../../responses/Response.php';
require_once __DIR__ . '/../../responses/ResponseController.php';
require_once __DIR__ . '/../../errors/InvalidPathError.php';
require_once __DIR__ . '/../../errors/ErrorController.php';
require_once __DIR__ . '/../../dbmappers/TargetGroupDBMapper.php';
require_once __DIR__ . '/../../models/TargetGroup.php';

class TargetGroupController extends ResponseController
{

    /**
     * TopicController constructor.
     * @param $path array Array containing the remaining path after v1/topics/ has been removed
     * @param $method string HTTP Request method
     * @param $body array Array containing request body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'TargetGroupDBMapper';
        $this->list_name = 'targetGroups';
        $this->model = 'TargetGroup';
    }

}