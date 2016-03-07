<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../errors/DBError.php';

class DocumentVersionTargetGroupDBMapper extends DBMapper
{

    public function get($target_group)
    {
        return $this->getById($target_group->getId());
    }

    public function getById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.dokument_version_target_group
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Action(
                    $row['document_version_id'],
                    $row['deadline'],
                    $row['mandatory'],
                    $row['description'],
                    $row['target_group_id'],
                    $row['action_id'],
                    $row['mandatory_id']);
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
}