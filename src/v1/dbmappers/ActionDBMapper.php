<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Action.php';
require_once __DIR__.'/../errors/DBError.php';

class ActionDBMapper extends DBMapper
{
    public function get($action)
    {
        $this->getById($action->getId());
    }

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.action
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Action(
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
        $actions= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.action";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($actions, new Action(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            $response = $actions;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($action)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.action
                VALUES (null, ?, ?);";
        $parameters = array(
            $action->getName(),
            $action->getDescription());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
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