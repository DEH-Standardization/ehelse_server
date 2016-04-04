<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../errors/DBError.php';

class StatusDBMapper extends DBMapper
{
    private $table_name = 'status';

    public function __construct()
    {
        parent::__construct();
        $this->model = 'Status';
    }

    /**
     * Returns status from database based on model
     * @param $id
     * @return DBError|Status
     */
    public function get($status)
    {
        $this->getById($status->getId());
    }




    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }
}