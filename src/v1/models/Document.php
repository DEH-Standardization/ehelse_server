<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';
require_once 'TargetGroup.php';
require_once 'Link.php';

class Document implements iModel
{

    const SQL_INSERT = "INSERT INTO document(title,description,status_id,sequence,document_type_id,topic_id, comment,
      standard_id,prev_document_id, internal_id, his_number) VALUES (:title, :description, :status_id, :sequence,
      :document_type_id, :topicId, :comment, :standard_id, :prev_document_id, :internal_id, :his_number);";
    const SQL_UPDATE = "INSERT INTO document(id,title,description,status_id,sequence,document_type_id,topic_id,comment,
      standard_id,prev_document_id, internal_id, his_number) VALUES (:id, :title, :description, :status_id,:sequence,
      :document_type_id, :topicId, :comment, :standard_id, :prev_document_id, :internal_id, :his_number)";
    const SQL_GET_BY_ID = "SELECT * FROM document WHERE id = :id AND is_archived = 0 AND (id,timestamp) IN
      (SELECT id, MAX(timestamp) FROM document GROUP BY id)";
    const SQL_GET_ALL = "SELECT * FROM document WHERE is_archived = 0 AND (id,timestamp) IN (SELECT id, MAX(timestamp)
      FROM document GROUP BY id);";
    const SQL_DELETE = "UPDATE document SET is_archived = 1 WHERE id = :id AND timestamp = :timestamp;";

    const SQL_GET_MAX_TIMESTAMP = "SELECT MAX(timestamp) FROM document WHERE id = :id;";
    const SQL_GET_NEXT_DOCUMENT_ID_BY_PREV_DOCUMENT_ID = "SELECT id from document WHERE prev_document_id = :id";
    const SQL_GET_PROFILE_IDS = "SELECT DISTINCT id FROM document WHERE standard_id = :id;";
    const SQL_GET_INTERNAL_ID = "SELECT internal_id FROM document  WHERE is_archived = 0 AND internal_id = :internal_id
      AND (id,timestamp) IN (SELECT id, MAX(timestamp) FROM document GROUP BY id);";
    const SQL_GET_HIS_NUMBER = "SELECT his_number FROM document  WHERE is_archived = 0 AND his_number = :his_number
      AND (id,timestamp) IN (SELECT id, MAX(timestamp) FROM document GROUP BY id);";

    const REQUIRED_POST_FIELDS = ['title', 'sequence', 'documentTypeId', 'topicId'];
    const REQUIRED_PUT_FIELDS = ['title', 'sequence', 'documentTypeId', 'topicId'];

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
        $next_document_id,
        $is_archived,
        $internal_id,
        $his_number,
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
     * @param $internal_id
     * @param $his_number
     */
    public function __construct($id, $timestamp, $title, $description, $sequence, $topic_id, $comment, $status_id,
                                $document_type_id, $standard_id, $prev_document_id, $is_archived, $internal_id,
                                $his_number)
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
        $this->internal_id = $internal_id;
        $this->his_number = $his_number;
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
     * @param $title
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
     * Sets description if it is valid, return the n first characters if it is too long
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = ModelValidation::getValidDescription($description);
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
        $this->comment = ModelValidation::getValidComment($comment);
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
        return $this->next_document_id;
    }

    public function setNextDocumentId($next_document_id)
    {
        $this->next_document_id = $next_document_id;
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

    public function getInternalId()
    {
        return $this->internal_id;
    }

    public function getHisNumber()
    {
        return $this->his_number;
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
            'nextDocumentId' => $this->next_document_id,
            'internalId' => $this->internal_id,
            'hisNumber' => $this->his_number,
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

    /**
     * Returns model from db array
     * @param $db_array
     * @return Document
     */
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
            $db_array['is_archived'],
            $db_array['internal_id'],
            $db_array['his_number']
        );
    }

    /**
     * Returns model from JSON
     * @param $json
     * @return Document
     */
    public static function fromJSON($json)
    {
        $document = new Document(
            getValueFromArray($json, 'id'),
            getValueFromArray($json, 'timestamp'),
            getValueFromArray($json, 'title'),
            getValueFromArray($json, 'description'),
            getValueFromArray($json, 'sequence'),
            getValueFromArray($json, 'topicId'),
            getValueFromArray($json, 'comment'),
            getValueFromArray($json, 'statusId'),
            getValueFromArray($json, 'documentTypeId'),
            getValueFromArray($json, 'standardId'),
            getValueFromArray($json, 'previousDocumentId'),
            null,
            getValueFromArray($json, 'internalId'),
            getValueFromArray($json, 'hisNumber')
        );

        $document->setLinks(getValueFromArray($json, 'links'));
        $document->setFields(getValueFromArray($json, 'fields'));
        $document->setTargetGroups(getValueFromArray($json, 'targetGroups'));

        return $document;
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
            ':status_id' => $this->status_id,
            ':sequence' => $this->sequence,
            ':document_type_id' => $this->document_type_id,
            ':topicId' => $this->topic_id,
            ':comment' => $this->comment,
            ':standard_id' => $this->standard_id,
            ':prev_document_id' => $this->prev_document_id,
            ':internal_id' => $this->internal_id,
            ':his_number' => $this->his_number
        );
        if ($this->id) {
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}