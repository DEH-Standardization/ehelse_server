<?php
require_once __DIR__ . '/StandardVersion.php';
require_once __DIR__.'/../dbmappers/DbCommunication.php';
require_once __DIR__.'/../models/ModelValidation.php';

class Standard
{
    private $id, $timestamp, $title, $description, $topic_id, $sequence, $comment;

    public static function  getStandardFromJSON($body)
    {
        $assoc = json_decode($body);
        return new Standard(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['sequence'],
            $assoc['topicId'],
            $assoc['comment']);
    }

    public function __construct($id, $timestamp, $title, $description, $sequence, $topic_id, $comment)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->topic_id = $topic_id;
        $this->sequence = $sequence;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setComment($comment);
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

    /**
     * Sets title if it is valid
     * @param $description
     */
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

    /**
     * Sets description if it is valid
     * @param $description
     */
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

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function setComment($comment)
    {
        if (strlen($comment) > ModelValidation::getCommentMaxLength($comment)) {
            $this->description = ModelValidation::getValidComment($comment);
            echo "comment is too long, set to: " . $this->comment;
        }
        else {
            $this->comment = $comment;
        }
    }

    public function getComment()
    {
        return $this->comment;
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
            'topicId' => $this->topic_id,
            'sequence' => $this->sequence,
            'comment' => $this->comment);
        return $assoc;
    }


}