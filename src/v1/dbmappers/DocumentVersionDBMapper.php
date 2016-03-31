<?php

require_once __DIR__.'/../models/DocumentVersion.php';
require_once __DIR__.'/../errors/DBError.php';
require_once 'DBMapper.php';

class DocumentVersionDBMapper extends DBMapper
{

    /**
     * Returns document
     * @param $id
     * @return DBError|DocumentVersion
     */
    public function get($document_version)
    {
        return $this->getById($document_version->getId());
    }

    /**
     * Returns document version by id
     * @param $id
     * @return DBError|DocumentVersion
     */
    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.document_version
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new DocumentVersion(
                    $row['id'],
                    $row['description'],
                    $row['status_id']);
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
     * Returns all
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $document_versions= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.document_version";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($document_versions, new DocumentVersion(
                    $row['id'],
                    $row['description'],
                    $row['status_id']));
            }
            $response = $document_versions;

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
    public function add($document_version)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.document_version
                VALUES (null, ?, ?);";
        $parameters = array(
            $document_version->getDescription(),
            $document_version->getStatusId());
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
    public function update($document_version)
    {
        if(!$this->isValidId($document_version->getId(), 'document_version')) {
            return new DBError('Invalid id');
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.document_version
                SET description = ?, status_id = ?
                WHERE id = ?;";
        $parameters = array(
            $document_version->getDescription(),
            $document_version->getStatusId(),
            $document_version->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $document_version->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}