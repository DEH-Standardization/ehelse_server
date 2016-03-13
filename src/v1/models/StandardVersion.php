<?php

class StandardVersion
{
    private $id, $timestamp, $standard_id, $document_version_id, $comment;



    public  function __construct($id, $timestamp, $standard_id, $document_version_id, $comment)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->standard_id = $standard_id;
        $this->setComment($comment);
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

    public function setComment($comment)
    {
        /*
        if (strlen($comment) > ModelValidation::getCommentMaxLength($comment)) {
            $this->description = ModelValidation::getValidComment($comment);
            echo "comment is too long, set to: " . $this->comment;
        }
        else {
            $this->comment = $comment;
        }
        */
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
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
            'standardId' => $this->standard_id,
            'documentId' => $this->document_id,
            'documentVersionId' => $this->document_version_id);
        return $assoc;
    }

}