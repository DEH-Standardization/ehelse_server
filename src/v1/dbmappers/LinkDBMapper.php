<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Link.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkDBMapper extends DBMapper
{
    private $table_name = 'link';

    /**
     * Returns link
     * @param $id
     * @return DBError|Link
     */
    public function get($link)
    {
        $this->getById($link->getId());
    }

    /**
     * Returns link based on id
     * @param $id
     * @return DBError|Link
     */
    public function getById($id)
    {
        $response = null;
        $sql = $sql = "SELECT * FROM $this->table_name WHERE id = ?;";
        try {
            $result = $this->queryDB($sql, array($id));
            $raw = $result->fetch();
            if($raw){
                $response =  Link::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all links
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $sql = "SELECT * FROM $this->table_name";
        try {
            $result = $this->queryDB($sql, array());
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

    /**
     * Adds element to database, and returns id of inserted element
     * @param $document_version
     * @return DBError|string
     */
    public function add($link)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(Link::SQL_INSERT_STATEMENT, $link->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Updates element in database, and returns id of updated element
     * @param $document_version
     * @return DBError|string
     */
    public function update($link)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray($link::SQL_UPDATE_STATEMENT, $link->toDBArray());
            $response = $link->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
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


    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }
}