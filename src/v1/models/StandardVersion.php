<?php

class StandardVersion
{
    private $id, $timestamp, $standard_id, $document_id, $document_version_id;

    public  function __construct($id, $timestamp, $standard_id, $document_id, $document_version_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->standard_id = $standard_id;
        $this->document_id = $document_id;
        $this->document_version_id = $document_version_id;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getStandardId()
    {
        return $this->standard_id;
    }

    public function setStandardId($standard_id)
    {
        $this->standard_id = $standard_id;
    }

    public function getDocumentId()
    {
        return $this->document_id;
    }

    public function setDocumentId($document_id)
    {
        $this->document_id = $document_id;
    }

    public function getDocumentVersionId()
    {
        return $this->document_version_id;
    }

    public function setDocumentVersionId($document_version)
    {
        $this->document_version_id = $document_version;
    }

    /**
     * Returns an associative array representation of the standard version model
     * @return array
     */
    public function toArray()
    {
        $assoc = array(
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'standard_id' => $this->standard_id,
            'document_id' => $this->document_id,
            'document_version_id' => $this->document_version_id);
        return $assoc;
    }

}