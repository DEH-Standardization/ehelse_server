<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/LinkCategory.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkCategoryDBMapper extends DBMapper
{
    private $table_name = 'link_category';

    /**
     * Returns link type
     * @param $id
     * @return DBError|Link
     */
    public function get($link_category)
    {
        $this->getById($link_category->getId());
    }

    /**
     * Returns link type based on id
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
                $response =  LinkCategory::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all link categories
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
                array_push($objects, LinkCategory::fromDBArray($raw_item));
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
    public function add($link_type)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(LinkCategory::SQL_INSERT_STATEMENT, $link_type->toDBArray());
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
    public function update($link_category)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray($link_category::SQL_UPDATE_STATEMENT, $link_category->toDBArray());
            $response = $link_category->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
    

    public function deleteById($id)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(LinkCategory::SQL_DELETE_LINK_CATEGORY_BY_ID,array(":id"=>$id));
            $response = array();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}