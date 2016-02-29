<?php
require_once __DIR__ . '/StandardVersion.php';
require_once '../dbmappers/DBCommunication.php';

class Standard
{
    private $id, $timestamp, $title, $description, $topic_id, $is_in_catalog, $sequence;

    public function __construct($id, $timestamp, $title, $description, $is_in_catalog, $sequence, $topic_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->topic_id = $topic_id;
        $this->is_in_catalog = $is_in_catalog;
        $this->sequence = $sequence;
        $this->setTitle($title);
        $this->setDescription($description);
    }

    public function getStandardVersions()
    {
        StandardVersion::getStandardVersionsByStandardId($this->id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function  setTitle($title)
    {
        if (strlen($title) > ModelValidation::getTitleMaxLength()) {
            $this->title = ModelValidation::getValidTitle($title);
            echo "Title is too long. Title set to: " . $this->title;
        } else {
            $this->title = $title;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        if (strlen($description) > ModelValidation::getDescriptionMaxLength($description)) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "description is too long. Description set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function getIsInCatalog()
    {
        return $this->is_in_catalog;
    }

    public function setIsInCatalog($is_in_catalog)
    {
        $this->is_in_catalog = $is_in_catalog;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }


}