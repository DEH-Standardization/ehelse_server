<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Link.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkDBMapper extends DBMapper
{
    /**
     * Returns link
     * @param $id
     * @return DBError|Link
     */
    public function get($link)
    {
        $this->getById($link->getId());
    }

    /**
     * Returns link based on id
     * @param $id
     * @return DBError|Link
     */
    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.link
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Link(
                    $row['id'],
                    $row['text'],
                    $row['description'],
                    $row['url'],
                    $row['link_type_id'],
                    $row['document_version_id']);
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
     * Returns all links
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $links= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.link";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($links, new Link(
                    $row['id'],
                    $row['text'],
                    $row['description'],
                    $row['url'],
                    $row['link_type_id'],
                    $row['document_version_id']));
            }
            if (count($links) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $links;
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
    public function add($link)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.link
                VALUES (null, ?, ?, ?, ?, ?);";
        $parameters = array(
            $link->getText(),
            $link->getDescription(),
            $link->getUrl(),
            $link->getLinkTypeId(),
            $link->getDocumentVersionId());
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
    public function update($link)
    {
        if(!$this->isValidId($link->getId(), "link")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.link
                SET text = ?, description = ?, url = ?, link_type_id = ?, document_version_id = ?
                WHERE id = ?;";
        $parameters = array(
            $link->getText(),
            $link->getDescription(),
            $link->getUrl(),
            $link->getLinkTypeId(),
            $link->getDocumentVersionId(),
            $link->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $link->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }


}