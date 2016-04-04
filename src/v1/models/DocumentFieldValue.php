<?php

require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';

class DocumentFieldValue implements iModel
{

    private $document_field_id, $value, $document_id, $document_timestamp;

    /**
     * DocumentFieldValue constructor.
     * @param $document_field_id
     * @param $value
     * @param $document_id
     * @param $document_timestamp
     */
    public function __construct($document_field_id, $value, $document_id, $document_timestamp)
    {
        $this->document_field_id = $document_field_id;
        $this->value = $value;
        $this->document_id = $document_id;
        $this->document_timestamp = $document_timestamp;
    }

    /**
     * @return mixed
     */
    public function getDocumentFieldId()
    {
        return $this->document_field_id;
    }

    /**
     * @param mixed $document_field_id
     */
    public function setDocumentFieldId($document_field_id)
    {
        $this->document_field_id = $document_field_id;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets value if it is valid, return the n first characters if it is too long
     * @param $value
     */
    public function setValue($value)
    {
        if (strlen($value) > ModelValidation::FIELD_VALUE_MAX_LENGTH) {
            $this->value = ModelValidation::getValidFieldValue($value);
            echo "Value is too long, set to: " . $this->value;
        } else {
            $this->value = $value;;
        }
    }

    public function getDocumentId()
    {
        return $this->document_id;
    }

    public function setDocumentId($document_id)
    {
        $this->document_id = $document_id;
    }

    public function getDocumentTimestamp()
    {
        return $this->document_timestamp;
    }

    public function setDocumentTimestamp($document_timestamp)
    {
        $this->document_timestamp = $document_timestamp;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        // TODO: check with API
        return array(
            'documentFieldId' => $this->document_field_id,
            'value' => $this->value,
            'documentId' => $this->document_id,
            'documentTimestamp' => $this->document_timestamp
        );
    }

    /**
     * Returns JSON representation of model
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray(),JSON_PRETTY_PRINT);
    }
}