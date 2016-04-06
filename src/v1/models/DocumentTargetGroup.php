<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class DocumentTargetGroup implements iModel
{
    const SQL_GET_ALL_TARGET_GROUPS_BY_DOCUMENT_ID = "SELECT target_group_id
                FROM document_target_group
                WHERE document_id = :document_id;";
    private $target_group_id, $deadline, $description, $action_id, $mandatory_id, $document_id, $document_timestamp;

    /**
     * DocumentTargetGroup constructor.
     * @param $target_group_id
     * @param $deadline
     * @param $description
     * @param $action_id
     * @param $mandatory_id
     * @param $document_id
     * @param $document_timestamp
     */
    public function __construct($target_group_id, $deadline, $description, $action_id, $mandatory_id, $document_id, $document_timestamp)
    {
        $this->target_group_id = $target_group_id;
        $this->deadline = $deadline;
        $this->description = $description;
        $this->action_id = $action_id;
        $this->mandatory_id = $mandatory_id;
        $this->document_id = $document_id;
        $this->document_timestamp = $document_timestamp;
    }

    public function getTargetGroupId()
    {
        return $this->target_group_id;
    }

    public function setTargetGroupId($target_group_id)
    {
        $this->target_group_id = $target_group_id;
    }

    public function getDeadline()
    {
        return $this->deadline;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
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

    public function getActionId()
    {
        return $this->action_id;
    }

    public function setActionId($action_id)
    {
        $this->action_id = $action_id;
    }

    public function getMandatoryId()
    {
        return $this->mandatory_id;
    }

    public function setMandatoryId($mandatory_id)
    {
        $this->mandatory_id = $mandatory_id;
    }

    public function getDocumentId()
    {
        return $this->document_id;
    }

    public function setDocumentId($document_id)
    {
        $this->document_id = $document_id;
    }

    public function getDocumentTimestamp()
    {
        return $this->document_timestamp;
    }

    public function setDocumentTimestamp($document_timestamp)
    {
        $this->document_timestamp = $document_timestamp;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        return array(
            'targetGroupId' => $this->target_group_id,
            'deadline' => $this->deadline,
            'description' => $this->description,
            'actionId' => $this->action_id,
            'mandatoryId' => $this->mandatory_id,
            'documentId' => $this->document_id,
            'documentTimestamp' => $this->document_timestamp);
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
        // TODO: Implement fromDBArray() method.
    }

    public static function fromJSON($json)
    {
        // TODO: Implement fromJSON() method.
    }

    public function toDBArray()
    {
        // TODO: Implement toDBArray() method.
    }
}