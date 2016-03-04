<?php

require_once 'DBMapper.php';
require_once __DIR__ . '/../models/StandardVersion.php';
require_once  __DIR__ . '/../errors/DBError.php';

class StandardVersionDBMapper extends DBMapper
{


    public function get($standard_version)
    {
        return $this->getById($standard_version->getId());
    }

    public function getById($id)
    {
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.standard_version WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM andrkje_ehelse_db.standard_version
                  GROUP BY id)";
        $result = $this->queryDB($sql, array($id));
        if ($result->rowCount() === 1) {
            $row = $result->fetch();
            return new StandardVersion(
                $row['id'],
                $row['timestamp'],
                $row['standard_id'],
                $row['document_id'],
                $row['document_version_id']);
        } else {
            return new DBError("Returned " . $result->rowCount() .
                " standard version, expected 1");
        }
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