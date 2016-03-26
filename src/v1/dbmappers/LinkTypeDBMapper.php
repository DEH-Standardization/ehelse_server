<?php


require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/LinkType.php';
require_once __DIR__.'/../errors/DBError.php';
class LinkTypeDBMapper extends DBMapper
{
    /**
     * Returns link type
     * @param $id
     * @return DBError|Link
     */
    public function get($link_type)
    {
        $this->getById($link_type->getId());
    }

    /**
     * Returns link type based on id
     * @param $id
     * @return DBError|Link
     */
    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.link_type
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new LinkType(
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

    /**
     * Returns all link types
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $link_types= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.link_type";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($link_types, new LinkType(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            $response = $link_types;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Adds element to database, and returns id of inserted element
     * @param $document_version
     * @return DBError|string
     */
    public function add($link_type)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.link_type
                VALUES (null, ?, ?);";
        $parameters = array(
            $link_type->getName(),
            $link_type->getDescription());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Updates element in database, and returns id of updated element
     * @param $document_version
     * @return DBError|string
     */
    public function update($link_type)
    {
        if(!$this->isValidId($link_type->getId(), "link_type")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.link_type
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $link_type->getName(),
            $link_type->getDescription(),
            $link_type->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $link_type->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }



}