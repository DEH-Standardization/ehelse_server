<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../errors/DBError.php';
require_once __DIR__.'/../models/DocumentTargetGroup.php';

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
                FROM document_target_group
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
        /*
        $response = null;
        $target_group_ids = array();
        //$dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select target_group_id
                from document_target_group
                where document_id = 2;";
        try {
            $result = $this->queryDB($sql, array($id));
            print_r($result);
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
        */

        echo "/n id = $id ";
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray("select * from document_target_group", array());
                //DocumentTargetGroup::SQL_GET_ALL_TARGET_GROUPS_BY_DOCUMENT_ID,
                //array(":document_id" => $id));
            print_r($result->fetchAll());
            echo gettype($result);
            $response = $result->fetch();
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


    public function getTargetGroupsByDocumentIdAndDocumentTimestamp($document_id, $document_timestamp) {
        try {
            $result = $this->queryDBWithAssociativeArray(DocumentTargetGroup::GET_DOCUMENT_TARGET_GROUPS_BY_DOCUMENT_ID,
                array(
                    ':document_id' => $document_id,
                    'document_timestamp' => $document_timestamp
                ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, DocumentTargetGroup::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}
