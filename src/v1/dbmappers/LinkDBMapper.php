<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Link.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkDBMapper extends DBMapper
{

    /**
     * LinkDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'Link';
    }

    public function getLinksByDocumentIdAndLinkCategoryId($link_category_id, $document_id)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(Link::SQL_GET_LINKS_BY_DOCUMENT_ID_AND_LINK_CATEGORY_ID, array(
                ':link_category_id' => $link_category_id,
                ':document_id' => $document_id
            ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, Link::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getLinkCategoriesIdByDocumentId($document_id)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(Link::GET_LINK_CATEGORY_IDS_BY_DOCUMENT_ID, array(
                ':document_id' => $document_id
            ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, $raw_item);
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getLinksByDocumentId($document_id, $document_timestamp)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(Link::SQL_GET_LINKS_BY_DOCUMENT_ID, array(
                ':document_id' => $document_id,
                ':document_timestamp' => $document_timestamp
            ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, Link::fromDBArray($raw_item));
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getLinkCategoriesByDocumentId($document_id)
    {
        try {
            $result = $this->queryDBWithAssociativeArray(Link::GET_LINK_CATEGORIES_BY_DOCUMENT_ID, array(
                ':document_id' => $document_id
            ));
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, $raw_item);
            }
            $response = $objects;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function addMultiple($links, $id, $timestamp)
    {
        foreach ($links as $link) {
            $link['documentId'] = $id;
            $link['documentTimestamp'] = $timestamp;
            $l = Link::fromJSON($link);
            $this->add($l);
        }
    }
    
}