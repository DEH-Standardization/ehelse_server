<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/Document.php";
require_once __DIR__. "/../errors/DBError.php";
require_once __DIR__. "/../dbmappers/DocumentFieldValueDBMapper.php";

class DocumentDBMapper extends DBMapper
{
    private $table_name = 'document';

    public function __construct()
    {
        parent::__construct();
        $this->model = 'Document';
    }

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

    /*
    public function getById($id)
    {
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
    */

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
     * @param $document_input
     * @return mixed
     */
    public function add($document_input)
    {
        $document_id = parent::add($document_input);
        return $this->addContentToDocument(
            $document_input->getTargetGroups(),
            $document_input->getLinks(),
            $document_input->getFields(),
            $document_id);
    }

    /**
     * Updates document in database by inserting new, returns id if success, error otherwise
     * @param $document_input
     * @return mixed
     */
    public function update($document_input)
    {
        $document_id = parent::update($document_input);
        return $this->addContentToDocument(
            $document_input->getTargetGroups(),
            $document_input->getLinks(),
            $document_input->getFields(),
            $document_id);

    }

    /**
     * Adds target groups, links and fields to document
     * @param $document_input
     * @param $document_id
     * @return mixed
     */
    private function addContentToDocument($target_groups, $links, $fields, $document_id)
    {
        $document = $this->getById($document_id);
        $document_target_group_db_mapper = new DocumentTargetGroupDBMapper();
        $document_link_db_mapper = new LinkDBMapper();
        $document_field_db_mapper = new DocumentFieldValueDBMapper();

        $id = $document->getId();
        $timestamp = $document->getTimestamp();

        $document_target_group_db_mapper->addMultiple($target_groups, $id, $timestamp);
        $document_link_db_mapper->addMultiple($links, $id, $timestamp);
        $document_field_db_mapper->addMultiple($fields, $id, $timestamp);

        return $document_id;
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