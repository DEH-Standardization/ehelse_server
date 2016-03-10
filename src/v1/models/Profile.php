<?php


class Profile
{
    // TODO: Remove $is_in_catalog
    // TODO: Add $comment

    private $id, $timestamp, $title, $description, $is_in_catalog, $sequence, $topic_id;

    public function __construct($id, $timestamp, $title, $description, $is_in_catalog, $sequence, $topic_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->is_in_catalog = $is_in_catalog;
        $this->sequence = $sequence;
        $this->topic_id = $topic_id;
    }

    public function setTitle($title)
    {
        if (strlen($title) > ModelValidation::getTitleMaxLength()) {
            $this->title = ModelValidation::getValidTitle($title);
            return "Title is too long. Title set to: " . $this->title;
        } else {
            $this->title = $title;
        }
    }

    public function setDescription($description)
    {
        if (strlen($description) > ModelValidation::getDescriptionMaxLength($description)) {
            $this->description = ModelValidation::getValidDescription($description);
            return "description is too long. Description set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
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

    public function getDescription()
    {
        return $this->description;
    }

    public function getOrder()
    {
        return $this->sequence;
    }

    public function setOrder($order)
    {
        $this->sequence = $order;
    }

    public  function getIsInCatalog()
    {
        return $this->is_in_catalog;
    }

    public function setIsInCatalog($is_in_catalog)
    {
        $this->is_in_catalog = $is_in_catalog;
    }

    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function toJSON()
    {
        $json = json_encode($this->toArray(),JSON_PRETTY_PRINT);
        /*
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                echo ' - No errors';
                break;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                echo ' - Unknown error';
                break;
        }
        */
        return $json;
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
            'isInCatalog' => $this->is_in_catalog,
            'sequence' => $this->sequence,
            'topicId' => $this->topic_id);

        return $assoc;
    }

}