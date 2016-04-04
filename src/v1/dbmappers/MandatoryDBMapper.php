<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Mandatory.php';
require_once __DIR__.'/../errors/DBError.php';

class MandatoryDBMapper extends DBMapper
{

    public function __construct()
    {
        parent::__construct();
        $this->model = 'Mandatory';
    }

    public function get($mandatory)
    {
        $this->getById($mandatory->getId());
    }


    public function update($mandatory)
    {
        if(!$this->isValidId($mandatory->getId(), "mandatory")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.mandatory
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $mandatory->getName(),
            $mandatory->getDescription(),
            $mandatory->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $mandatory->getId();
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