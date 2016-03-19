<?php

class DocumentType
{
    private $id, $name;

    /**
     * DocumentType constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name if it is valid
     * @param $description
     */
    public function setName($name)
    {
        if (strlen($name) > ModelValidation::getNameMaxLength()) {
            $this->name = ModelValidation::getValidDescription($name);
            echo "Name is too long. Name set to: " . $this->description;
        }
        else {
            $this->name = $name;
        }
    }

}