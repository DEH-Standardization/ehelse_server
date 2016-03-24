<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/Link.php';
require_once __DIR__.'/../errors/DBError.php';

class LinkDBMapper extends DBMapper
{
    private $table_name = DBCommunication::DATABASE_NAME.'.link';

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
        $sql = "SELECT *
                FROM $this->table_name
                WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Link(
                    $row['id'],
                    $row['text'],
                    $row['description'],
                    $row['url'],
                    $row['link_category_id'],
                    $row['document_id'],
                    $row['document_timestamp'],
                    $row['link_document_id']);
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
     * Returns all links
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $links= array();
        $sql = "SELECT * FROM $this->table_name;";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($links, Link(
                    $row['id'],
                    $row['text'],
                    $row['description'],
                    $row['url'],
                    $row['link_category_id'],
                    $row['document_id'],
                    $row['document_timestamp'],
                    $row['link_document_id']));
            }
            if (count($links) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $links;
            }
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
        $link = new Link(null,null,null,null,null,null,null,null);
        $response = null;
        $sql = "INSERT INTO $this->table_name
                VALUES (null, ?, ?, ?, ?, ?, ?, ?);";
        $parameters = array(
            $link->getText(),
            $link->getDescription(),
            $link->getUrl(),
            $link->getLinkCategoryId(),
            $link->getDocumentId(),
            $link->getDocumentTimestamp(),
            $link->getLinkDocumentId());
        try {
            $this->queryDB($sql, $parameters);
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
        if(!$this->isValidId($link->getId(), "link")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $sql = "UPDATE $this->table_name
                SET text = ?, description = ?, url = ?, link_category_id = ?, document_id = ?,
                  document_timestamp = ?, link_document_id = ?
                WHERE id = ?;";
        $parameters = array(
            $link->getText(),
            $link->getDescription(),
            $link->getUrl(),
            $link->getLinkCategoryId(),
            $link->getDocumentId(),
            $link->getDocumentTimestamp(),
            $link->getLinkDocumentId(),
            $link->getId());
        try {
            $this->queryDB($sql, $parameters);
            return $link->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getLinksByDocumentVersionIdAndLinkTypeId($link_type_id, $document_version_id)
    {
        $response = null;
        $sql = "select *
                from $this->table_name
                where link_type_id = ? and document_version_id = ?";
        $links = array();
        try {
            $result = $this->queryDB($sql, array($link_type_id, $document_version_id));
            foreach ($result as $row) {
                array_push($links, new Link(
                    $row['id'],
                    $row['text'],
                    $row['description'],
                    $row['url'],
                    $row['link_type_id'],
                    $row['document_version_id']));
            }
            if (count($links) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $links;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getLinkTypeIdByDocumentVersionId($id)
    {
        $response = null;
        $sql = "SELECT distinct link_type_id
                FROM $this->table_name
                WHERE document_version_id = ?;";
        $links_type_ids = array();
        try {
            $result = $this->queryDB($sql, array($id));
            foreach ($result as $row) {
                array_push($links_type_ids, $row['link_type_id']);
            }
            if (count($links_type_ids) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $links_type_ids;
            }
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