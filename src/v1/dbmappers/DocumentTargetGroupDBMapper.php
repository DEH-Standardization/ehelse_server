<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../errors/DBError.php';

class DocumentTargetGroupDBMapper extends DBMapper
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
    

    /**
     * Returns a list of target group ids connected to a document version
     * @param $id
     * @return array|DBError
     */
    public function getAllTargetGroupIdsByDocumentVersionId($id)
    {
        $response = null;
        $target_group_ids = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select target_group_id
                from $dbName.dokument_version_target_group
                where document_version_id = ?;";
        try {
            $result = $this->queryDB($sql, array($id));
            foreach ($result as $row) {
                array_push($target_group_ids,
                    $row['target_group_id']);
            }
            if (count($target_group_ids) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $target_group_ids;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}
