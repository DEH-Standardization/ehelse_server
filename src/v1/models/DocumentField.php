<?php

require_once __DIR__.'/ModelValidation.php';

class DocumentField
{
    private $id, $name, $description, $sequence, $mandatory, $is_standard_field, $is_profiles_field;

    /**
     * DocumentField constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $sequence
     * @param $mandatory
     * @param $is_standard_field
     * @param $is_profiles_field
     */
    public function __construct($id, $name, $description, $sequence, $mandatory, $is_standard_field, $is_profile_field)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->sequence = $sequence;
        $this->mandatory = $mandatory;
        $this->is_standard_field = $is_standard_field;
        $this->is_profile_field = $is_profile_field;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (strlen($name) > ModelValidation::getNameMaxLength($name)) {
            $this->name = ModelValidation::getValidName($name);
            return "name is too long. Description set to: " . $this->description;
        }
        else {
            $this->name = $name;
        }
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

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
    }

    public function getIsStandardField()
    {
        return $this->is_standard_field;
    }

    public function setIsStandardField($is_standard_field)
    {
        $this->is_standard_field = $is_standard_field;
    }

    public function getIsProfileField()
    {
        return $this->is_profile_field;
    }

    public function setIsProfileField($is_profile_field)
    {
        $this->is_profile_field = $is_profile_field;
    }

    public function toArray()
    {
        $assoc = array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'mandatory' => $this->mandatory,
            'isStandardField' => $this->is_standard_field,
            'isProfileField' => $this->is_profile_field);
        return $assoc;
    }

    public function toJSON()
    {
        return json_encode($this->toArray(),JSON_PRETTY_PRINT);
    }

}