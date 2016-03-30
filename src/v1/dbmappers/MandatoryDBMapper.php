<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Mandatory.php';
require_once __DIR__.'/../errors/DBError.php';

class MandatoryDBMapper extends DBMapper
{
    public function get($mandatory)
    {
        $this->getById($mandatory->getId());
    }

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.mandatory
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Mandatory(
                    $row['id'],
                    $row['name'],
                    $row['description']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " profiles, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        $response = null;
        $mandatories= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.mandatory";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($mandatories, new Mandatory(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            $response = $mandatories;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($mandatory)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.mandatory
                VALUES (null, ?, ?);";
        $parameters = array(
            $mandatory->getName(),
            $mandatory->getDescription());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
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

}