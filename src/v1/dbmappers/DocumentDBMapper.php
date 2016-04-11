<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/Document.php";
require_once __DIR__. "/../errors/DBError.php";

class DocumentDBMapper extends DBMapper
{
    private $table_name = 'document';

    /**
     * Returns document from database based on model
     * @param $id
     * @return DBError|Document
     */
    public function get($model)
    {
        $this->getById($model->getId());
    }

    /**
     * Returns document from database based on id
     * @param $id
     * @return DBError|Document
     */
    public function getById($id)
    {
        /*
        $response = null;
        $sql = "SELECT *
                FROM $this->table_name WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $this->table_name
                GROUP BY id)";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Document(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
                    $row['comment'],
                    $row['status_id'],
                    $row['document_type_id']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    ", expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
        */


        $response = null;
        $sql = "SELECT *
                FROM $this->table_name WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $this->table_name
                GROUP BY id)";
        try {
            $result = $this->queryDB($sql, array($id));
            $raw = $result->fetch();
            if($raw){
                $response = Document::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all the newest logged documents
     * @return array|DBError
     */
    public function getAll()
    {
        /*
        $response = null;
        $documents = array();
        $sql = "SELECT *
                FROM $this->table_name WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $this->table_name
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            foreach ($result as $row) {
                array_push($documents, new Document(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
                    $row['comment'],
                    $row['status_id'],
                    $row['document_type_id']));
            }
            if (count($documents) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $documents;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
        */

        $response = null;
        $sql = "SELECT *
                FROM $this->table_name WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $this->table_name
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, Document::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Adds new document to database, returns id if success, error otherwise
     * @param $document
     * @return DBError|string
     */
    public function add($document)
    {

        $result = null;
        $sql = "INSERT INTO $this->table_name
                VALUES (null, now(), ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $parameters = array(
            $document->getTitle(),
            $document->getDescription(),
            $document->getSequence(),
            $document->getTopicId(),
            $document->getComment(),
            $document->getStatusId(),
            $document->getDocumentTypeId(),
            $document->getNextDocumentId(),
            $document->getPrevDocumentId());
        try {
            if($this->queryDB($sql, $parameters)) {
                $result = $this->connection->lastInsertId();    // Sets id of the updated standard version
            }
        } catch(PDOException $e) {
            $result = new DBError($e);  // Sets DBError
        }
        return $result;
        /*
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(Document::SQL_INSERT_STATEMENT, $document->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
        */

    }

    /**
     * Updates document in database by inserting new, returns id if success, error otherwise
     * @param $document
     * @return DBError|string
     */
    public function update($document)
    {
        if(!$this->isValidId($document->getId(), $this->table_name)) {
            return new DBError("Invalid id");
        }
        $result = null;
        $sql = "INSERT INTO $this->table_name
                VALUES (?, now(), ?, ?, ?, ?, ?, ?, ?);";
        $parameters = array(
            $document->getId(),
            $document->getTitle(),
            $document->getDescription(),
            $document->getSequence(),
            $document->getTopicId(),
            $document->getComment(),
            $document->getStatusId(),
            $document->getDocumentTypeId());
        try {
            if($this->queryDB($sql, $parameters)) {
                $result = $this->connection->lastInsertId();
            }
        } catch(PDOException $e) {
            $result = new DBError($e);
        }
        return $result;
    }

    public function getDocumentsByTopicId($topic_id)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(Topic::SQL_GET_DOCUMENTS_BY_TOPIC_ID,
                array(':topic_id' => $topic_id));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, Document::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}