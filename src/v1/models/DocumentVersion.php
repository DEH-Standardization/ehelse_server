<?php


class DocumentVersion
{
    private $id, $description, $status_id;

    public function __construct($id, $description, $status_id)
    {
        $this->id = $id;
        $this->setDescription($description);
        $this->status_id = $status_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    /**
     * Returns associated array
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'description' => $this->description,
            'status_id' => $this->status_id);
    }

}