<?php

require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';

class DocumentType implements iModel
{
    const SQL_INSERT_STATEMENT = "INSERT INTO user(name) VALUES (:name);";
    const SQL_UPDATE_STATEMENT = "UPDATE user SET name=:name WHERE id = :id;";


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
     * Sets name if it is valid, return the n first characters if it is too long
     * @param $name
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

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
        // TODO: check with API
        return array(
            'id' => $this->id,
            'name' => $this->name);
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
        return new DocumentType($db_array['id'],
            $db_array['name']);
    }


    public static function fromJSON($json)
    {
        return new DocumentType($json['id'],
            $json['name']);
    }

    public function toDBArray()
    {
        $db_array = array(
            ":name" => $this->name
        );
        if($this->id){
            $db_array[":id"] = $this->id;
        }
        return $db_array;
    }
}