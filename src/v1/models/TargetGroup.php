<?php

require_once 'ModelValidation.php';
require_once 'iModel.php';

class TargetGroup implements iModel
{
    const REQUIRED_POST_FIELDS = ['name', 'description', 'parentId', 'abbreviation'];
    const REQUIRED_PUT_FIELDS = ['name', 'description', 'parentId', 'abbreviation'];
    private $id, $name, $description, $parent_id, $abbreviation;
    const SQL_INSERT_STATEMENT = "INSERT INTO target_group(name, description, parent_id, abbreviation) VALUES (:name, :description, :parent_id, :abbreviation);";
    const SQL_UPDATE_STATEMENT = "UPDATE target_group set name=:name, description=:description, parent_id=:parent_id, abbreviation=:abbreviation WHERE id=:id;";


    /**
     * Status constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $parent_id
     * @param $abbreviation
     */
    public function __construct($id, $name, $description, $parent_id, $abbreviation)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
        $this->parent_id = $parent_id;
        $this->abbreviation = $abbreviation;
    }

    public static function fromJSON($json)
    {
        return new TargetGroup(
            $json['id'],
            $json['name'],
            $json['description'],
            $json['parentId'],
            $json['abbreviation']
        );
    }

    public static function fromDBArray($row)
    {
        return new TargetGroup(
            $row['id'],
            $row['name'],
            $row['description'],
            $row['parent_id'],
            $row['abbreviation']);
    }

    public function toDBArray()
    {
        $db_array = array(
            ":name" => $this->name,
            ":description" => $this->description,
            ":parent_id" => $this->parent_id,
            ":abbreviation" => $this->abbreviation
        );
        if($this->id){
            $db_array[":id"] = $this->id;
        }
        return $db_array;
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
        }
        else {
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
            'description' => $this->description,
            'parentId' => $this->parent_id,
            'abbreviation' => $this->abbreviation);
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