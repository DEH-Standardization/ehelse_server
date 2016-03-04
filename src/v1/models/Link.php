<?php

class Link
{
    private $id, $text, $description, $url, $link_type_id, $document_version_id;

    /**
     * Link constructor.
     * @param $id
     * @param $text
     * @param $description
     * @param $url
     * @param $link_type_id
     * @param $document_version_id
     */
    public function __construct($id, $text, $description, $url, $link_type_id, $document_version_id)
    {
        $this->id = $id;
        $this->text = $text;
        $this->setDescription($description);
        $this->url = $url;
        $this->link_type_id = $link_type_id;
        $this->document_version_id = $document_version_id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets text if it is valid
     * @param $description
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets text if it is valid
     * @param $description
     */
    public function setText($text)
    {
        if (strlen($text) > ModelValidation::getDescriptionMaxLength()) {
            $this->text = ModelValidation::getValidDescription($text);
            echo "description is too long. Description set to: " . $this->text;
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
     * Sets description if it is valid
     * @param $description
     */
    public function setDescription($description)
    {
        if (strlen($description) > ModelValidation::getDescriptionMaxLength()) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "description is too long. Description set to: " . $this->description;
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

    public function getLinkTypeId()
    {
        return $this->link_type_id;
    }

    public function setLinkTypeId($link_type_id)
    {
        $this->link_type_id = $link_type_id;
    }

    public function getDocumentVersionId()
    {
        return $this->document_version_id;
    }

    public function setDocumentVersionId($document_version_id)
    {
        $this->document_version_id = $document_version_id;
    }

    /**
     * Returns associated array
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'text' => $this->text,
            'description' => $this->description,
            'url' => $this->url,
            'link_type_id' => $this->link_type_id,
            'document_version_id' => $this->document_version_id);
    }

}