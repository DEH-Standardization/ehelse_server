<?php
require_once __DIR__ . '/ModelValidation.php';
require_once __DIR__ . '/iModel.php';
require_once __DIR__ . '/../../utils.php';

class Status implements iModel
{

    const SQL_GET_ALL = "SELECT * FROM status;";
    const SQL_GET_BY_ID = "SELECT * FROM status WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO status VALUES (null, :name, :description);";
    const SQL_UPDATE = "UPDATE status SET name = :name, description = :description WHERE id = :id;";
    const SQL_DELETE = "DELETE FROM status WHERE id = :id;";

    const REQUIRED_POST_FIELDS = ['name'];
    const REQUIRED_PUT_FIELDS = ['name'];

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
        $this->name = ModelValidation::getValidName($name);
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

    /**
     * Returns associated array
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
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Returns JSON representation of model
     * @param $db_array
     * @return Action
     */
    public static function fromDBArray($db_array)
    {
        return new Status(
            $db_array['id'],
            $db_array['name'],
            $db_array['description']
        );
    }

    /**
     * Returns model from JSONy
     * @param $json
     * @return Action
     */
    public static function fromJSON($json)
    {
        return new Status(
            getValueFromArray($json, 'id'),
            getValueFromArray($json, 'name'),
            getValueFromArray($json, 'description')
        );
    }

    /**
     * Returns associative array for sql querying
     * @return array
     */
    public function toDBArray()
    {
        $db_array = array(
            ':name' => $this->name,
            ':description' => $this->description
        );
        if ($this->id) {
            $db_array[':id'] = $this->id;
        }
        return $db_array;
    }
}