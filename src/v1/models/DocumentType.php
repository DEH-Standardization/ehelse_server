<?php

require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';
require_once __DIR__ . '/../../utils.php';

class DocumentType implements iModel
{

    const SQL_GET_ALL = "SELECT * FROM document_type;";
    const SQL_GET_BY_ID = "SELECT * FROM document_type WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO document_type VALUES (null, :name);";
    const SQL_UPDATE = "UPDATE document_type SET name = :name WHERE id = :id;";
    const SQL_DELETE = "DELETE FROM document_type WHERE id = :id;";

    const REQUIRED_POST_FIELDS = ['name'];
    const REQUIRED_PUT_FIELDS = ['name'];

    private $id, $name;

    /**
     * DocumentType constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->setName($name);
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
        $this->name = ModelValidation::getValidName($name);
    }

    /**
     * Returns associated array representation of model
     * @return array
     */
    public function toArray()
    {
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
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Returns model from db array
     * @param $db_array
     * @return DocumentType
     */
    public static function fromDBArray($db_array)
    {
        return new DocumentType($db_array['id'],
            $db_array['name']);
    }

    /**
     * Returns model from JSON
     * @param $json
     * @return DocumentType
     */
    public static function fromJSON($json)
    {
        return new DocumentType(
            getValueFromArray($json, 'id'),
            getValueFromArray($json, 'name')
        );
    }

    /**
     * Returns associative array for sql querying
     * @return array
     */
    public function toDBArray()
    {
        $db_array = array(
            ":name" => $this->name
        );
        if ($this->id) {
            $db_array[":id"] = $this->id;
        }
        return $db_array;
    }
}