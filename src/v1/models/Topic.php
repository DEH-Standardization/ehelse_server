<?php

require_once __DIR__ . '/../dbmappers/TopicDBMapper.php';
require_once __DIR__ . '/iModel.php';

class Topic implements iModel
{
    const SQL_GET_ALL = "SELECT * FROM topic WHERE is_archived = 0 AND (id,timestamp) IN (SELECT id, MAX(timestamp)
                  FROM topic GROUP BY id);";

    const SQL_GET_BY_ID = "SELECT * FROM topic WHERE id = :id AND is_archived = 0 AND (id,timestamp) IN
                (SELECT id, MAX(timestamp) FROM topic GROUP BY id)";
    const SQL_INSERT = "INSERT INTO topic VALUES (null, null, :title, :description, :sequence, :parent_id, :comment, 0);";
    const SQL_UPDATE = "INSERT INTO topic VALUES (:id, null, :title, :description, :sequence, :parent_id, :comment, 0);";
    //const SQL_DELETE = "DELETE FROM action WHERE id = :id;";  // TODO: delete this
    const SQL_GET_DOCUMENTS_BY_TOPIC_ID = "SELECT DISTINCT * FROM document WHERE is_archived = 0 AND (id,timestamp) IN
                ( SELECT id, MAX(timestamp)FROM document GROUP BY id) AND topic_id = :topic_id;";

    const SQL_GET_SUBTOPICS = "SELECT * FROM topic WHERE parent_id = :id AND is_archived = 0 AND (id,timestamp) IN
                ( SELECT id, MAX(timestamp)FROM topic GROUP BY id);";
    const SQL_GET_MAX_TIMESTAMP = "SELECT MAX(timestamp) FROM topic WHERE id = :id;";
    const SQL_DELETE = "UPDATE topic SET is_archived = 1 WHERE id = :id AND
                timestamp = :timestamp;";

    const REQUIRED_POST_FIELDS = ['title', 'sequence'];
    const REQUIRED_PUT_FIELDS = ['id', 'title', 'sequence'];

    private $id, $timestamp, $title, $description, $sequence, $parent_id, $comment, $children, $documents;

    /**
     * Topic constructor.
     * @param $id
     * @param $timestamp
     * @param $title
     * @param $description
     * @param $sequence
     * @param $parent_id
     * @param $comment
     */
    public function __construct($id, $timestamp, $title, $description, $sequence, $parent_id, $comment)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->sequence = $sequence;
        $this->parent_id = $parent_id;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setComment($comment);
        $this->documents = [];
        $this->children = [];
    }

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

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets title if it is valid
     * @param $description
     */
    public function setTitle($title)
    {
        $this->title = ModelValidation::getValidTitle($title);
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
        $this->description = ModelValidation::getValidDescription($description);
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

    public function setComment($comment)
    {
        $this->description = ModelValidation::getValidComment($comment);
    }

    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Returns JSON representation of model
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        $children = [];
        foreach ($this->children as $child) {
            array_push($children, $child->toArray());
        }
        $documents = [];
        foreach ($this->documents as $document) {
            array_push($documents, $document->toArray());
        }
        $assoc = array(
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'parentId' => $this->parent_id,
            'comment' => $this->comment,
            'children' => $children,
            'documents' => $documents);
        return $assoc;
    }

    public static function fromDBArray($assoc)
    {
        return new Topic(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['sequence'],
            $assoc['parent_id'],
            $assoc['comment']);
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    public function addChild($child)
    {
        array_push($this->children, $child);
    }

    public function addChildren($children)
    {
        $this->children = array_merge($this->children, $children);
    }

    public function addDocument($document)
    {
        array_push($this->documents, $document);
    }

    /**
     * Returns model from JSON
     * @param $json
     * @return Topic
     */
    public static function fromJSON($json)
    {
        return new Topic(
            getValueFromArray($json, 'id'),

            getValueFromArray($json, 'timestamp'),
            getValueFromArray($json, 'title'),
            getValueFromArray($json, 'description'),
            getValueFromArray($json, 'sequence'),
            getValueFromArray($json, 'parentId'),
            getValueFromArray($json, 'comment'),
            getValueFromArray($json, 'children')
        );
    }

    /**
     * Returns associative array for sql querying
     * @return array
     */
    public function toDBArray()
    {
        $db_array = array(
            ':title' => $this->title,
            ':description' => $this->description,
            ':sequence' => $this->sequence,
            ':parent_id' => $this->parent_id,
            ':comment' => $this->comment
        );
        if ($this->id) {
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}