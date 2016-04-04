<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class Mandatory implements iModel
{
    const SQL_GET_ALL = "SELECT * FROM mandatory;";
    const SQL_GET_BY_ID = "SELECT * FROM mandatory WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO mandatory VALUES (null, :name, :description);";
    const SQL_UPDATE = "UPDATE mandatory SET name = :name, description = :description WHERE id = :id;";

    const REQUIRED_POST_FIELDS = ['name', 'description'];
    const REQUIRED_PUT_FIELDS = ['name', 'description'];

    private $id, $name, $description;

    /**
     * Status constructor.
     * @param $id
     * @param $name
     * @param $description
     */
    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
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

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
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
        return new Action(
            $db_array['id'],
            $db_array['name'],
            $db_array['description']);
    }

    public static function fromJSON($json)
    {
        return new Action(
            $json['id'],
            $json['name'],
            $json['description']);
    }

    public function toDBArray()
    {
        $db_array = array(
            ':name' => $this->name,
            ':description' => $this->description
        );
        if($this->id){
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}