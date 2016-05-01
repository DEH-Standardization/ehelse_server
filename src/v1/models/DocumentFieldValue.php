<?php

require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';

class DocumentFieldValue implements iModel
{

    const SQL_GET_FIELDS_BY_DOCUMENT_ID = "SELECT * FROM document_field_value
      WHERE document_id = :document_id AND document_timestamp = :document_timestamp;";
    const SQL_GET_ALL = "SELECT * FROM document_field_value;";
    const SQL_GET_BY_ID = "SELECT * FROM document_field_value WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO document_field_value VALUES (:document_field_id, :value, :document_id,
      :document_timestamp);";

    const REQUIRED_POST_FIELDS = ['document_field_id', 'value', 'document_id', 'document_timestamp'];
    const REQUIRED_PUT_FIELDS = ['document_field_id', 'value', 'document_id', 'document_timestamp'];

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
        return array(
            'fieldId' => $this->document_field_id,
            'value' => $this->value
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

    /**
     * Returns model from db array
     * @param $db_array
     * @return DocumentFieldValue
     */
    public static function fromDBArray($db_array)
    {
        return new DocumentFieldValue(
            $db_array['document_field_id'],
            $db_array['value'],
            $db_array['document_id'],
            $db_array['document_timestamp']
        );
    }

    /**
     * Returns model from JSON
     * @param $json
     * @return DocumentFieldValue
     */
    public static function fromJSON($json)
    {
        return new DocumentFieldValue(
            $json['fieldId'],
            $json['value'],
            $json['documentId'],
            $json['documentTimestamp']
        );
    }

    /**
     * Returns associative array for sql querying
     * @return array
     */
    public function toDBArray()
    {
        return array(
            ':document_field_id' => $this->document_field_id,
            ':value' => $this->value,
            ':document_id' => $this->document_id,
            ':document_timestamp' => $this->document_timestamp
        );
    }
}