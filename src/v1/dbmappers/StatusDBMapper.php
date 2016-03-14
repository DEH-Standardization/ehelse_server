<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../errors/DBError.php';

class StatusDBMapper extends DBMapper
{

    public function get($status)
    {
        $this->getById($status->getId());
    }

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.status
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Status(
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
        $statuses= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.status";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($statuses, new Status(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            if (count($statuses) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $statuses;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($status)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.status
                VALUES (null, ?, ?);";
        $parameters = array(
            $status->getName(),
            $status->getDescription());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($status)
    {
        if(!$this->isValidId($status->getId(), "status")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.status
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
}