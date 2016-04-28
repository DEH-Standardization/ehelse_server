<?php


class DocumentFieldValueDBMapper extends DBMapper
{

    /**
     * DocumentFieldValueDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentFieldValue';
    }

    public function getFieldsByDocumentIdAndDocumentTimestamp($document_id, $document_timestamp)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(DocumentFieldValue::SQL_GET_FIELDS_BY_DOCUMENT_ID, array(
                ':document_id' => $document_id,
                ':document_timestamp' => $document_timestamp
            ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, DocumentFieldValue::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;

    }

    /**
     * Adds multiple DocumentFieldValues form JSON list
     * @param $fields
     * @param $id
     * @param $timestamp
     */
    public function addMultiple($fields, $id, $timestamp)
    {
        foreach ($fields as $field) {
            $field['documentId'] = $id;
            $field['documentTimestamp'] = $timestamp;
            print_r($this->add(DocumentFieldValue::fromJSON($field)));
        }
    }



}