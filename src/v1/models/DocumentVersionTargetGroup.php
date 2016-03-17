<?php

class DocumentVersionTargetGroup
{
    private $document_version_id, $deadline, $mandatory, $description, $target_group_id, $action_id, $mandatory_id;

    /**
     * DocumentVersionTargetGroup constructor.
     * @param $document_version_id
     * @param $deadline
     * @param $mandatory
     * @param $description
     * @param $target_group_id
     * @param $action_id
     * @param $mandatory_id
     */
    public function __construct($document_version_id, $deadline, $mandatory, $description, $target_group_id, $action_id, $mandatory_id)
    {
        $this->document_version_id = $document_version_id;
        $this->deadline = $deadline;
        $this->mandatory = $mandatory;
        $this->setDescription($description);
        $this->target_group_id = $target_group_id;
        $this->action_id = $action_id;
        $this->mandatory_id = $mandatory_id;
    }

    public function getDocumentVersionId()
    {
        return $this->document_version_id;
    }

    public function setDocumentVersionId($document_version_id)
    {
        $this->document_version_id = $document_version_id;
    }

    public function getDeadline()
    {
        return $this->deadline;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
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
        if (strlen($description) > ModelValidation::getDescriptionMaxLength()) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "description is too long. Description set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    public function getTargetGroupId()
    {
        return $this->target_group_id;
    }

    public function setTargetGroupId($target_group_id)
    {
        $this->target_group_id = $target_group_id;
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

    /**
    * Returns an associative array representation of the model
    * @return array
    */
    public function toArray()
    {
        $assoc = array(
            'document_version_id' => $this->document_version_id,
            'deadline' => $this->deadline,
            'mandatory' => $this->mandatory,
            'description' => $this->description,
            'target_group_id' => $this->target_group_id,
            'action_id' => $this->action_id,
            'mandatory_id' => $this->mandatory_id);
        return $assoc;
    }

}