<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class LinkCategory implements iModel
{
    const REQUIRED_POST_FIELDS = ['name'];
    const REQUIRED_PUT_FIELDS = ['name'];
    const SQL_INSERT_STATEMENT = "INSERT INTO link_category(id,name,description) VALUES (null,:name,:description);";
    const SQL_UPDATE_STATEMENT = "UPDATE link_category SET name=:name,description=:description WHERE id=:id";
    private $id, $name, $description;

    /**
     * LinkCategory constructor.
     * @param $id
     * @param $name
     * @param $description
     */
    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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
     * Sets name if it is valid, return the n first characters if it is too long
     * @param $description
     */
    public function setName($name)
    {
        if (strlen($name) > ModelValidation::NAME_MAX_LENGTH) {
            $this->name = ModelValidation::getValidName($name);
            echo "Name is too long, set to: " . $this->name;
        }
        else {
            $this->name = $name;
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

    public function toArray()
    {
        // TODO: check with API description
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description);
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
        return new LinkCategory(
            $db_array['id'],
            $db_array['name'],
            $db_array['description']
        );
    }

    public static function fromJSON($json)
    {
        return new LinkCategory(
            $json['id'],
            $json['name'],
            $json['description']
        );
    }

    public function toDBArray()
    {
        $db_array = array(
            ':name' => $this->name,
            ':description' => $this->description
        );
        if($this->id){
            $db_array[":id"] = $this->id;
        }
        return $db_array;
    }
}