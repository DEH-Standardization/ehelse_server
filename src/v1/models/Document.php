<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';
require_once 'TargetGroup.php';
require_once 'Link.php';

class Document implements iModel
{
    const REQUIRED_POST_FIELDS = ['title','description','status','sequence','documentType','topicId'];
    const SQL_INSERT_STATEMENT = "INSERT INTO document(title,description,status,sequence,documentType,topicId,
                                  comment,status_id,document_type_id,next_document_id,prev_document_id)
                                  VALUES (:title,:description,:status,:sequence,:documentType,:topicId,
                                  :comment,:status_id,:document_type_id,:next_document_id,:prev_document_id);";
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
        $next_document_id,
        $prev_document_id,
        $target_groups,
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
     * @param $document_type_id
     * @param $next_document_id
     * @param $prev_document_id
     */
    public function __construct($id, $timestamp, $title, $description, $sequence,
                                $topic_id, $comment, $status_id, $document_type_id,
                                $next_document_id, $prev_document_id)
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
        $this->next_document_id = $next_document_id;
        $this->prev_document_id = $prev_document_id;
        $this->target_groups = [];
        $this->links = [];
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


    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        // TODO: check with API description
        return array(
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'topicId' => $this->topic_id,
            'comment' => $this->comment,
            'status' => $this->status_id,
            'documentType' => $this->document_type_id,
            'targetGroups' => $this->target_groups,
            'links' => $this->links
        );
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
            $db_array['next_document_id'],
            $db_array['prev_document_id']
        );
    }

    public static function fromJSON($json)
    {
        // TODO: Implement fromJSON() method.
    }

    public function toDBArray()
    {
        $db_array = array(
            ":title" => $this->title,
            ":description" => $this->description,
            ":sequence" => $this->sequence,
            ":topic_id" => $this->topic_id,
            ":comment" => $this->comment,
            ":status_id" => $this->status_id,
            ":document_type_id" => $this->document_type_id,
            ":next_document_id" => $this->next_document_id,
            ":prev_document_id" => $this->prev_document_id,
        );
        if($this->id){
            $db_array[":id"] = $this->id;
        }
        return $db_array;
    }
}