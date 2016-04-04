<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Action.php';
require_once __DIR__.'/../errors/DBError.php';

class ActionDBMapper extends DBMapper
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Action';
    }

    public function get($action)
    {
        $this->getById($action->getId());
    }




    public function update($action)
    {
        if(!$this->isValidId($action->getId(), "action")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.action
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $action->getName(),
            $action->getDescription(),
            $action->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $action->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function delete($model)
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented error");
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        throw new Exception("Not implemented error");
    }
}