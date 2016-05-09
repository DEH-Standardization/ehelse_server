<?php

require_once "DBMapper.php";
require_once __DIR__ . "/../models/Document.php";
require_once __DIR__ . "/../errors/DBError.php";
require_once __DIR__ . "/../dbmappers/DocumentFieldValueDBMapper.php";

class DocumentDBMapper extends DBMapper
{

    /**
     * DocumentDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Document';
    }

    /**
     * Delete document based on id
     * Archive variable is set to true, nothing is removed from database.
     * @param $id
     * @return array|DBError|null
     */
    public function deleteById($id)
    {
        $response = null;
        try {
            $timestamp = $this->queryDBWithAssociativeArray(
                Document::SQL_GET_MAX_TIMESTAMP, array(':id' => $id)
            )->fetch()[0];  // gets the string representation of timestamp from database
            $this->queryDBWithAssociativeArray(
                Document::SQL_DELETE,
                array(
                    ':id' => $id,
                    ':timestamp' => $timestamp)
            );
            $response = [];
        } catch (PDOException $e) {
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

    /**
     * Retrieving document id of document with prev_document_id = document_id
     * @param $document_id
     * @return DBError|mixed|null
     */
    public function getNextDocumentIdByDocumentId($document_id){
        $response = null;
        try {
            $response = $this->queryDBWithAssociativeArray(
                Document::SQL_GET_NEXT_DOCUMENT_ID_BY_PREV_DOCUMENT_ID, array(':id' => $document_id)
            )->fetch();
            $response = $response ? $response["id"] : null;
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all documents under a topic
     * @param $topic_id
     * @return array|DBError|null
     */
    public function getDocumentsByTopicId($topic_id)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(Topic::SQL_GET_DOCUMENTS_BY_TOPIC_ID,
                array(':topic_id' => $topic_id));
            $raw = $result->fetchAll();
            $objects = [];
            foreach ($raw as $raw_item) {
                array_push($objects, Document::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Return list of profiles under a document
     * @param $id
     * @return array|DBError|null
     */
    public function getProfiles($id)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(Document::SQL_GET_PROFILES, array(':id' => $id));
            $raw = $result->fetchAll();
            $objects = [];
            foreach ($raw as $raw_item) {
                array_push($objects, Document::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Return list of profile ids under a document
     * @param $id
     * @return array|DBError|null
     */
    public function getProfileIds($id)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(Document::SQL_GET_PROFILE_IDS, array(':id' => $id));
            $raw = $result->fetchAll();
            $objects = [];
            foreach ($raw as $raw_item) {
                array_push($objects, array('id' => $raw_item['id']));
            }
            $response = $objects;

        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns if internal id is unique
     * @param $internal_id
     * @return bool|null
     */
    public function isValidInternalId($internal_id)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(
                Document::SQL_GET_INTERNAL_ID,
                array(':internal_id' => $internal_id));
            $raw = $result->fetchAll();

            if ($raw) {
                $response = false;
            } else {
                $response = true;
            }

        } catch (PDOException $e) {
            $response = false;
        }
        return $response;
    }

    /**
     * Returns if his number is unique
     * @param $his_number
     * @return bool|null
     */
    public function isValidHisNumber($his_number)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(
                Document::SQL_GET_HIS_NUMBER,
                array(':his_number' => $his_number));
            $raw = $result->fetchAll();

            if ($raw) {
                $response = false;
            } else {
                $response = true;
            }

        } catch (PDOException $e) {
            $response = false;
        }
        return $response;
    }

}