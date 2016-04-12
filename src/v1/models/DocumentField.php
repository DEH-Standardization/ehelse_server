<?php

require_once __DIR__.'/ModelValidation.php';

class DocumentField
{
    const REQUIRED_POST_FIELDS = ['name'];
    const REQUIRED_PUT_FIELDS = ['name'];
    const SQL_GET_ALL = "SELECT * FROM document_field;";
    const SQL_GET_BY_ID = "SELECT * FROM document_field WHERE id = :id;";
    const SQL_INSERT = "INSERT INTO document_field VALUES (null, :name);";
    const SQL_UPDATE = "UPDATE document_field SET name = :name, description = :description, sequence = :sequence, mandatory = :mandatory, document_type_id = :document_type_id WHERE id = :id;";
    const SQL_DELETE_DOCUMENT_FIELD_BY_ID = "DELETE FROM document_field WHERE id=:id";
    private $id, $name, $description, $sequence, $mandatory, $document_type_id;

    /**
     * DocumentField constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $sequence
     * @param $mandatory
     * @param $document_type_id
     */
    public function __construct($id, $name, $description, $sequence, $mandatory, $document_type_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->sequence = $sequence;
        $this->mandatory = $mandatory;
        $this->document_type_id = $document_type_id;
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
        if (strlen($name) > ModelValidation::NAME_MAX_LENGTH) {
            $this->name = ModelValidation::getValidName($name);
            return "Name is too long, set to: " . $this->name;
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
        if (strlen($description) > ModelValidation::DESCRIPTION_MAX_LENGTH) {
            $this->description = ModelValidation::getValidDescription($description);
            return "Description is too long, set to: " . $this->description;
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

    public function getDocumentTypeId()
    {
        return $this->document_type_id;
    }

    public function setDocumentTypeId($document_type_id)
    {
        $this->document_type_id = $document_type_id;
    }

    public function toArray()
    {
        $assoc = array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'mandatory' => $this->mandatory,
            'documentTypeId' => $this->document_type_id);
        return $assoc;
    }

    public function toJSON()
    {
        return json_encode($this->toArray(),JSON_PRETTY_PRINT);
    }
    public function fromJSON($json)
    {
        return new DocumentField(
            getValueFromArray($json,'id'),
            getValueFromArray($json,'name'),
            getValueFromArray($json,'description'),
            getValueFromArray($json,'sequence'),
            getValueFromArray($json,'mandatory'),
            getValueFromArray($json,'documentTypeId')
        );
    }

    public static function fromDBArray($db_array)
    {
        return new DocumentField(
            $db_array['id'],
            $db_array['name'],
            $db_array['description'],
            $db_array['sequence'],
            $db_array['mandatory'],
            $db_array['document_type_id']
        );
    }

}