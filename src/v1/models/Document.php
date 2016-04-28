<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';
require_once 'TargetGroup.php';
require_once 'Link.php';

class Document implements iModel
{
    const REQUIRED_POST_FIELDS = ['title', 'sequence', 'documentTypeId','topicId'];
    const REQUIRED_PUT_FIELDS = ['title', 'sequence', 'documentTypeId','topicId'];

    const SQL_INSERT = "INSERT INTO document(title,description,status_id,sequence,document_type_id,topic_id,
                                  comment,standard_id,prev_document_id)
                                  VALUES (:title,:description,:status_id,:sequence,:document_type_id,:topicId,
                                  :comment,:standard_id,:prev_document_id);";
    const SQL_UPDATE = "INSERT INTO document(id,title,description,status_id,sequence,document_type_id,topic_id,
                                  comment,standard_id,prev_document_id)
                                  VALUES (
                                  :id,
                                  :title,
                                  :description,
                                  :status_id,
                                  :sequence,
                                  :document_type_id,
                                  :topicId,
                                  :comment,
                                  :standard_id,
                                  :prev_document_id)";
    const SQL_GET_BY_ID = "SELECT * FROM document WHERE id = :id AND is_archived = 0 AND (id,timestamp) IN
                (SELECT id, MAX(timestamp) FROM document GROUP BY id)";
    const SQL_GET_ALL = "SELECT * FROM document WHERE is_archived = 0 AND (id,timestamp) IN (SELECT id, MAX(timestamp)
                  FROM document GROUP BY id);";
    const SQL_DELETE = "UPDATE document SET is_archived = 1 WHERE id = :id AND timestamp = :timestamp;";

    const SQL_GET_MAX_TIMESTAMP = "SELECT MAX(timestamp) FROM document WHERE id = :id;";
    const SQL_GET_PROFILE_IDS = "SELECT DISTINCT id FROM document WHERE standard_id = :id;";

    private
        $id,
        $timestamp,
        $title,
        $description,
        $sequence,
        $topic_id,
        $comment,
        $status_id,
        $document_type_id,
        $standard_id,
        $prev_document_id,
        $is_archived,
        $profiles,
        $target_groups,
        $fields,
        $links;

    /**
     * Document constructor.
     * Document constructor.
     * @param $id
     * @param $timestamp
     * @param $title
     * @param $description
     * @param $sequence
     * @param $topic_id
     * @param $comment
     * @param $status_id
     * @param $is_archived
     * @param $document_type_id
     * @param $standard_id
     * @param $prev_document_id
     */
    public function __construct($id, $timestamp, $title, $description, $sequence,
                                $topic_id, $comment, $status_id, $document_type_id,
                                $standard_id, $prev_document_id, $is_archived)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->sequence = $sequence;
        $this->topic_id = $topic_id;
        $this->setComment($comment);
        $this->status_id = $status_id;
        $this->document_type_id = $document_type_id;
        $this->standard_id = $standard_id;
        $this->prev_document_id = $prev_document_id;
        $this->is_archived = $is_archived;
        $this->target_groups = [];
        $this->links = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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
     * Sets title if it is valid, return the n first characters if it is too long
     * @param $description
     */
    public function setTitle($title)
    {
        if (strlen($title) > ModelValidation::TITLE_MAX_LENGTH) {
            $this->title = ModelValidation::getValidTitle($title);
            echo "Title is too long, set to: " . $this->title;
        } else {
            $this->title = $title;
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
        } else {
            $this->description = $description;
        }
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets comment if it is valid, return the n first characters if it is too long
     * @param $comment
     */
    public function setComment($comment)
    {
        if (strlen($comment) > ModelValidation::COMMENT_MAX_LENGTH) {
            $this->comment = ModelValidation::getValidComment($comment);
            echo "Comment is too long, set to: " . $this->comment;
        } else {
            $this->comment = $comment;
        }
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getDocumentTypeId()
    {
        return $this->document_type_id;
    }

    public function setDocumentTypeId($document_type_id)
    {
        $this->document_type_id = $document_type_id;
    }

    public function getNextDocumentId()
    {
        return $this->standard_id;
    }

    public function setNextDocumentId($standard_id)
    {
        $this->standard_id = $standard_id;
    }

    public function getPrevDocumentId()
    {
        return $this->prev_document_id;
    }

    public function setPrevDocumentId($prev_document_id)
    {
        $this->prev_document_id = $prev_document_id;
    }

    public function getProfiles()
    {
        return $this->profiles;
    }

    public function setProfiles($profiles)
    {
        $this->profiles = $profiles;
    }

    public function setTargetGroups($target_groups)
    {
        $this->target_groups = $target_groups;
    }

    public function getTargetGroups()
    {
        return $this->target_groups;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'description' => $this->description,
            'statusId' => $this->status_id,
            'sequence' => $this->sequence,
            'topicId' => $this->topic_id,
            'comment' => $this->comment,
            'documentTypeId' => $this->document_type_id,
            'standardId' => $this->standard_id,
            'previousDocumentId' => $this->prev_document_id,
            'profiles' => $this->profiles,
            'links' => $this->links,
            'fields' => $this->fields,
            'targetGroups' => $this->target_groups
        );
    }

    /**
     * Returns JSON representation of model
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public static function fromDBArray($db_array)
    {
        return new Document(
            $db_array['id'],
            $db_array['timestamp'],
            $db_array['title'],
            $db_array['description'],
            $db_array['sequence'],
            $db_array['topic_id'],
            $db_array['comment'],
            $db_array['status_id'],
            $db_array['document_type_id'],
            $db_array['standard_id'],
            $db_array['prev_document_id'],
            $db_array['is_archived']
        );
    }

    public static function fromJSON($json)
    {
        $document = new Document(
            (array_key_exists('id', $json)) ? $json['id'] : null,
            (array_key_exists('timestamp', $json)) ? $json['timestamp'] : null,
            $json['title'],
            (array_key_exists('description', $json)) ? $json['description'] : null,
            $json['sequence'],
            $json['topicId'],
            (array_key_exists('comment', $json)) ? $json['comment'] : null,
            (array_key_exists('statusId', $json)) ? $json['statusId'] : null,
            $json['documentTypeId'],
            (array_key_exists('standardId', $json)) ? $json['standardId'] : null,
            (array_key_exists('previousDocumentId', $json)) ? $json['previousDocumentId'] : null,
            null
        );

        $document->setLinks((array_key_exists('links', $json)) ? $json['links'] : []);
        $document->setFields((array_key_exists('fields', $json)) ? $json['fields'] : []);
        $document->setTargetGroups((array_key_exists('targetGroups', $json)) ? $json['targetGroups'] : []);

        return $document;
    }

    public function toDBArray()
    {
        $db_array = array(
            ':title' => $this->title,
            ':description' => $this->description,
            ':status_id' => $this->status_id,
            ':sequence' => $this->sequence,
            ':document_type_id' => $this->document_type_id,
            ':topicId' => $this->topic_id,
            ':comment' => $this->comment,
            ':standard_id' => $this->standard_id,
            ':prev_document_id' => $this->prev_document_id
        );
        if ($this->id) {
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}