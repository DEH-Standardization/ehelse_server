<?php
require_once __DIR__.'/../dbmappers/TopicDBMapper.php';

/**
 * Class Topic Model
 */
class Topic{
    private $id, $timestamp, $title, $description, $number, $is_in_catalog, $sequence, $parent_id;

    /**
     * Topic constructor.
     * @param $id
     * @param $timestamp
     * @param $title
     * @param $description
     * @param $number
     * @param $is_in_catalog
     * @param $sequence
     * @param $parent_id
     */
    public function __construct($id, $timestamp, $title, $description, $number, $is_in_catalog, $sequence, $parent_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->number = $number;
        $this->is_in_catalog = $is_in_catalog;
        $this->sequence = $sequence;
        $this->parent_id = $parent_id;
        $this->setTitle($title);
        $this->setDescription($description);
    }

    /**
     * Returns a new topic with previus inserted id
     * @param $title
     * @param $description
     * @param $number
     * @param $is_in_catalog
     * @param $sequence
     * @param $parent_id
     * @return DBError|null|string|Topic
     */
    /*  NOT IN USE
    public static function createNewTopic($title, $description, $number, $is_in_catalog, $sequence, $parent_id)
    {

        $mapper = new TopicDbMapper();
        $result = $mapper->add(new Topic(null,null,$title, $description, $number, $is_in_catalog, $sequence, $parent_id));
        if ($result instanceof DBError) {
            return $result;
        } else {
            return $mapper->getTopicById($result);
        }
    }
    */

    public static function getLastInsertedTopic()
    {

    }

    public function getID()
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

    public function  getTitle()
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

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getIsInCatalog()
    {
        return $this->is_in_catalog;
    }

    public function setIsInCatalog($is_in_catalog)
    {
        $this->is_in_catalog = $is_in_catalog;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function toJSON()
    {
        return json_encode($this->toArray(),JSON_PRETTY_PRINT);
    }

    /**
     * Returns associated array
     * @return array
     */
    public function toArray()
    {
        $assoc = array(
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'description' => $this->description,
            'number' => $this->number,
            'isInCatalog' => $this->is_in_catalog,
            'sequence' => $this->sequence,
            'parent' => $this->parent_id,
            'children' => array(),
            'documents' => array());
        return $assoc;
    }

}