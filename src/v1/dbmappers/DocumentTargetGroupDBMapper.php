<?php

require_once 'DBMapper.php';
require_once __DIR__.'/../errors/DBError.php';
require_once __DIR__.'/../models/DocumentTargetGroup.php';

class DocumentTargetGroupDBMapper extends DBMapper
{

    public function __construct()
    {
        parent::__construct();
        $this->model = 'DocumentTargetGroup';
    }


    public function delete($model)
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented error");
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        throw new Exception("Not implemented error");
    }


    /**
     * Return target groups for a specific document
     * @param $document_id
     * @param $document_timestamp
     * @return array|DBError
     */
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

    /**
     * Adds multiple DocumentTargetGroups form JSON list
     * @param $target_groups
     * @param $id
     * @param $timestamp
     */
    public function addMultiple($target_groups, $id, $timestamp)
    {
        foreach ($target_groups as $target_group) {
            $target_group['documentId'] = $id;
            $target_group['documentTimestamp'] = $timestamp;
            $this->add(DocumentTargetGroup::fromJSON($target_group));
        }
    }

}
