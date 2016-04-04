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




    /**
     * Updates status in database
     * @param $status
     * @return DBError|string
     */
    public function update($status)
    {
        if(!$this->isValidId($status->getId(), "status")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::DATABASE_NAME;
        $sql = "UPDATE $this->table_name
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $status->getName(),
            $status->getDescription(),
            $status->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $status->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
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