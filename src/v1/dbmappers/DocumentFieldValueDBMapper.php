<?php


class DocumentFieldValueDBMapper extends DBMapper
{


    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentFieldValue';
    }


    public function addMultiple($fields, $id, $timestamp)
    {
        echo "field!! " ;
        print_r($fields);
        foreach ($fields as $field) {
            $field['documentId'] = $id;
            $field['documentTimestamp'] = $timestamp;
            $this->add(DocumentFieldValue::fromJSON($field));
        }
    }

}