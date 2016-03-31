<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class Link implements iModel
{
    private $id, $text, $description, $url, $link_category_id, $document_id, $document_timestamp, $link_document_id;

    /**
     * Link constructor.
     * @param $id
     * @param $text
     * @param $description
     * @param $url
     * @param $link_category_id
     * @param $document_id
     * @param $document_timestamp
     * @param $link_document_id
     */
    public function __construct($id, $text, $description, $url, $link_category_id, $document_id, $document_timestamp, $link_document_id)
    {
        $this->id = $id;
        $this->setText($text);
        $this->setDescription($description);
        $this->setUrl($url);
        $this->link_category_id = $link_category_id;
        $this->document_id = $document_id;
        $this->document_timestamp = $document_timestamp;
        $this->link_document_id = $link_document_id;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets text if it is valid, return the n first characters if it is too long
     * @param $text
     */
    public function setText($text)
    {
        if (strlen($text) > ModelValidation::TEXT_MAX_LENGTH) {
            $this->text = ModelValidation::getValidText($text);
            echo "Text is too long, set to: " . $this->text;
        }
        else {
            $this->text = $text;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets description if it is valid, return the n first characters if it is too long
     * @param $description
     */
    public function setDescription($description)
    {
        if (strlen($description) > ModelValidation::DESCRIPTION_MAX_LENGTH) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "Description is too long, set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getLinkCategoryId()
    {
        return $this->link_category_id;
    }

    public function setLinkCategoryId($link_type_id)
    {
        $this->link_category_id = $link_type_id;
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

    public function getLinkDocumentId()
    {
        return $this->link_document_id;
    }

    public function setLinkDocumentId($link_document_id)
    {
        $this->link_document_id = $link_document_id;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        // TODO: check with API
        return array(
            'id' => $this->id,
            'text' => $this->text,
            'description' => $this->description,
            'url' => $this->url,
            'linkCategoryId' => $this->link_category_id,
            'documentId' => $this->document_id,
            'documentTimestamp' => $this->document_timestamp,
            'linkDocumentId' => $this->document_id);
    }

    /**
     * Returns JSON representation of model
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray(),JSON_PRETTY_PRINT);
    }

    public static function fromDBArray($db_array)
    {
        // TODO: Implement fromDBArray() method.
    }

    public static function fromJSON($json)
    {
        // TODO: Implement fromJSON() method.
    }

    public function toDBArray()
    {
        // TODO: Implement toDBArray() method.
    }
}