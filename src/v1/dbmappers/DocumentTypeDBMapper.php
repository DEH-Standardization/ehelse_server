<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/DocumentType.php";
require_once __DIR__. "/../errors/DBError.php";


class DocumentTypeDBMapper extends DBMapper
{

    private $table_name = 'document_type';


    public function get($model)
    {
    $this->getById($model->getById());
    }

    public function getById($id)
    {
        $response = null;
        $sql = "SELECT * FROM $this->table_name WHERE id = ?";
        try {
            $result = $this->queryDB($sql, array($id));
            $raw = $result->fetch();
            if($raw){
                $response = DocumentType::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }


    public function getAll()
    {
        $response = null;
        $sql = "SELECT * FROM $this->table_name";
        try {
            $result = $this->queryDB($sql, array());
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, DocumentType::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($document_type)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(DocumentType::SQL_INSERT_STATEMENT, $document_type->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($document_type)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(DocumentType::SQL_UPDATE_STATEMENT, $document_type->toDBArray());
            $response = $document_type->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }
}