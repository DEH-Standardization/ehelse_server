<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class DocumentTargetGroup implements iModel
{
    const SQL_INSERT = "INSERT INTO document_target_group VALUES (:target_group_id, :deadline, :description, :action_id,
      :mandatory_id, :document_id, :document_timestamp);";

    const SQL_GET_ALL_TARGET_GROUPS_BY_DOCUMENT_ID = "SELECT target_group_id FROM document_target_group
      WHERE document_id = :document_id;";
    const GET_DOCUMENT_TARGET_GROUPS_BY_DOCUMENT_ID = "SELECT * FROM document_target_group
      WHERE document_id = :document_id AND document_timestamp = :document_timestamp;";

    const REQUIRED_POST_FIELDS = ['target_group_id', 'action_id', 'mandatory_id', 'document_id', 'document_timestamp'];
    const REQUIRED_PUT_FIELDS = ['target_group_id', 'action_id', 'mandatory_id', 'document_id', 'document_timestamp'];

    private
        $target_group_id,
        $deadline,
        $description,
        $action_id,
        $mandatory_id,
        $document_id,
        $document_timestamp,
        $target_group,
        $action,
        $mandatory;

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
        $this->setDescription($description);
        $this->action_id = $action_id;
        $this->mandatory_id = $mandatory_id;
        $this->document_id = $document_id;
        $this->document_timestamp = $document_timestamp;
        $this->target_group = [];
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
        $this->description = ModelValidation::getValidDescription($description);
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

    public function getTargetGroup()
    {
        return $this->target_group;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        return array(
            'documentId' => $this->document_id,
            'description' => $this->description,
            'actionId' => $this->action_id,
            'deadline' => $this->deadline,
            'mandatoryId' => $this->mandatory_id,
            'targetGroupId' => $this->target_group_id
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
        return new DocumentTargetGroup(
            $db_array['target_group_id'],
            $db_array['deadline'],
            $db_array['description'],
            $db_array['action_id'],
            $db_array['mandatory_id'],
            $db_array['document_id'],
            $db_array['document_timestamp']
        );
    }

    public static function fromJSON($json)
    {
        return new DocumentTargetGroup(
            getValueFromArray($json,'targetGroupId'),
            getValueFromArray($json,'deadline'),
            getValueFromArray($json,'description'),
            getValueFromArray($json,'actionId'),
            getValueFromArray($json,'mandatoryId'),
            getValueFromArray($json,'documentId'),
            getValueFromArray($json,'documentTimestamp')
        );
    }

    public function toDBArray()
    {
       return array(
           ':target_group_id' => $this->target_group_id,
           ':deadline' => $this->deadline,
           ':description' => $this->description,
           ':action_id' => $this->action_id,
           ':mandatory_id' => $this->mandatory_id,
           ':document_id' => $this->document_id,
           ':document_timestamp' => $this->document_timestamp
       );
    }
}