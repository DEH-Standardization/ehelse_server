<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../models/DocumentField.php';
require_once __DIR__.'/../errors/DBError.php';

class DocumentFieldDBMapper extends DBMapper
{

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.document_field
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new DocumentField(
                    $row['id'],
                    $row['name'],
                    $row['description'],
                    $row['sequence'],
                    $row['mandatory'],
                    $row['document_type_id']);
            } 
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        $response = null;
        $actions= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.document_field";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($actions, new DocumentField(
                    $row['id'],
                    $row['name'],
                    $row['description'],
                    $row['sequence'],
                    $row['mandatory'],
                    $row['document_type_id']));
            }
            $response = $actions;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAllIds()
    {
        // TODO: Implement getAllIds() method.
    }

    public function add($document_field)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.document_field
                VALUES (null, ?, ?, ?, ?, ?);";
        $parameters = array(
            $document_field->getName(),
            $document_field->getDescription(),
            $document_field->getSequence(),
            $document_field->getMandatory(),
            $document_field->getDocumentTypeId());
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($document_field)
    {
        if(!$this->isValidId($document_field->getId(), "document_field")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.document_field
                SET `name` = ?, description = ?, sequence = ?, mandatory = ?, document_type_id = ?
                WHERE id = ?;";
        $parameters = array(
            $document_field->getName(),
            $document_field->getDescription(),
            $document_field->getSequence(),
            $document_field->getMandatory(),
            $document_field->getDocumentTypeId(),
            $document_field->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $document_field->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
    

    public function deleteById($id)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(DocumentField::SQL_DELETE_DOCUMENT_FIELD_BY_ID,array(":id"=>$id));
            $response = array();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}