<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../errors/DBError.php';

class StatusDBMapper extends DBMapper
{
    private $table_name = 'status';

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
     * Returns status from database based on id
     * @param $id
     * @return DBError|Status
     */
    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::DATABASE_NAME;
        $sql = "SELECT *
                FROM $dbName.$this->table_name
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
                    ", expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all statuses
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $statuses= array();
        $dbName = DbCommunication::DATABASE_NAME;
        $sql = "SELECT * FROM $dbName.$this->table_name";
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

    /**
     * Adds new status to database, returns id if success, error otherwise
     * @param $status
     * @return DBError|string
     */
    public function add($status)
    {
        $response = null;
        $db_name = DbCommunication::DATABASE_NAME;
        $sql = "INSERT INTO $db_name.$this->table_name
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
        $db_name = DbCommunication::DATABASE_NAME;
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

    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }
}