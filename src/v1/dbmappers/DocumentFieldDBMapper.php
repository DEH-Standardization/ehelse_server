<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../models/DocumentField.php';
require_once __DIR__.'/../errors/DBError.php';

class DocumentFieldDBMapper extends DBMapper
{
    public function get($target_group)
    {
        // TODO: Implement get() method.
    }

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
                    $row['is_standard_field'],
                    $row['is_profile_field']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " profile, expected 1");
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
                    $row['is_standard_field'],
                    $row['is_profile_field']));
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
                VALUES (null, ?, ?, ?, ?, ?, ?);";
        $parameters = array(
            $document_field->getName(),
            $document_field->getDescription(),
            $document_field->getSequence(),
            $document_field->getMandatory(),
            $document_field->getIsStandardField(),
            $document_field->getIsProfileField());
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
                SET `name` = ?, description = ?, sequence = ?, mandatory = ?, is_standard_field = ?, is_profile_field = ?
                WHERE id = ?;";
        $parameters = array(
            $document_field->getName(),
            $document_field->getDescription(),
            $document_field->getSequence(),
            $document_field->getMandatory(),
            $document_field->getIsStandardField(),
            $document_field->getIsProfileField(),
            $document_field->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $document_field->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }


}