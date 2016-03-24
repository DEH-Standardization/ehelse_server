<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/LinkCategory.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkCategoryDBMapper extends DBMapper
{
    private $table_name = 'link_category';
    private $dbName = DbCommunication::DATABASE_NAME;

    /**
     * Returns link type
     * @param $id
     * @return DBError|Link
     */
    public function get($link_category)
    {
        $this->getById($link_category->getId());
    }

    /**
     * Returns link type based on id
     * @param $id
     * @return DBError|Link
     */
    public function getById($id)
    {
        $response = null;
        $sql = "SELECT *
                FROM $this->dbName.$this->table_name
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new LinkCategory(
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
     * Returns all link categories
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $link_types= array();
        $sql = "SELECT * FROM $this->dbName.$this->table_name";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($link_types, new LinkCategory(
                    $row['id'],
                    $row['name'],
                    $row['description']));
            }
            if (count($link_types) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $link_types;
            }
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
        $sql = "INSERT INTO $this->dbName.$this->table_name
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
    public function update($link_category)
    {
        if(!$this->isValidId($link_category->getId(), $this->table_name)) {
            return new DBError("Invalid id");
        }
        $response = null;
        $sql = "UPDATE $this->dbName.$this->table_name
                SET name = ?, description = ?
                WHERE id = ?;";
        $parameters = array(
            $link_category->getName(),
            $link_category->getDescription(),
            $link_category->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $link_category->getId();
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