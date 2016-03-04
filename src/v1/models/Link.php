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
        $this->description = $description;
        $this->url = $url;
        $this->link_type_id = $link_type_id;
        $this->document_version_id = $document_version_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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