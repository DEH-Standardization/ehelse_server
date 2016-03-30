<?php

require_once "DBMapper.php";
require_once __DIR__. "/../models/DocumentType.php";
require_once __DIR__. "/../errors/DBError.php";


class DocumentTypeDBMapper extends DBMapper
{

    private $table_name = DbCommunication::DATABASE_NAME.'.document_type';


    public function get($model)
    {

    }

    public function getById($id)
    {
        $response = null;
        $sql = "SELECT *
                FROM $this->table_name
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new DocumentType(
                    $row['id'],
                    $row['name']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    ", expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function add($model)
    {
        // TODO: Implement add() method.
    }

    public function update($model)
    {
        // TODO: Implement update() method.
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