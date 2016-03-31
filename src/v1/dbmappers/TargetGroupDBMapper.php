<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/TargetGroup.php';
require_once __DIR__.'/../errors/DBError.php';

class TargetGroupDBMapper extends DBMapper
{
    public function get($target_group)
    {
        $this->getById($target_group->getId());
    }

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.target_group
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new TargetGroup(
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
        $target_groups= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.target_group";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($target_groups, new TargetGroup(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            $response = $target_groups;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($target_group)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.target_group
                VALUES (null, ?, ?);";
        $parameters = array(
            $target_group->getName(),
            $target_group->getDescription());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($target_group)
    {
        if(!$this->isValidId($target_group->getId(), "target_group")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.target_group
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $target_group->getName(),
            $target_group->getDescription(),
            $target_group->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $target_group->getId();
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