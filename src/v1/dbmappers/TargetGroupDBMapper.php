<?php

class TargetGroupDBMapper extends DBMapper
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
                FROM $dbName.target_group WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.target_group
                GROUP BY id)";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Profile(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " profiles, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
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