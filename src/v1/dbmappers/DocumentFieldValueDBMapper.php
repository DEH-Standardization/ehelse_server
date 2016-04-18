<?php


class DocumentFieldValueDBMapper extends DBMapper
{


    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentFieldValue';
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
            $this->add(DocumentFieldValue::fromJSON($field));
        }
    }

}