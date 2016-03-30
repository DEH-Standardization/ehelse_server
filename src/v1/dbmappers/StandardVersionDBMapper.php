<?php

require_once 'DBMapper.php';
require_once __DIR__ . '/../models/StandardVersion.php';
require_once  __DIR__ . '/../errors/DBError.php';

class StandardVersionDBMapper extends DBMapper
{


    /**
     * Returns standard version from database
     * @param $standard_version
     * @return DBError|StandardVersion
     */
    public function get($standard_version)
    {
        return $this->getById($standard_version->getId());
    }

    /**
     * Returns standard version from database based on id
     * @param $id
     * @return DBError|StandardVersion
     */
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
                $row['document_version_id'],
                $row['comment']);
        } else {
            return new DBError("Returned " . $result->rowCount() .
                " standard version, expected 1");
        }
    }

    /**
     * Returns all the newest logged standard versions
     * @return array|DBError
     */
    public function getAll()
    {
        $response = null;
        $standard_versions = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.standard_version WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.standard_version
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            foreach ($result as $row) {
                array_push($standard_versions, new StandardVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['standard_id'],
                    $row['document_version_id'],
                    $row['comment']));
            }
            if (count($standard_versions) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $standard_versions;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Adds new standard version to database, returns id if success, error otherwise
     * @param $standard_version
     * @return DBError|int
     */
    public function add($standard_version)
    {
        $result = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.standard_version
                VALUES (null, now(), ?, ?, ?);";
        $parameters = array(
            $standard_version->getStandardId(),
            $standard_version->getDocumentVersionId(),
            $standard_version->getComment());
        try {
            if($this->queryDB($sql, $parameters)) {
                $result = $this->connection->lastInsertId();  // Sets id of the updated standard version
            }
        } catch(PDOException $e) {
            $result = new DBError($e);     // Sets DBError
        }
        return $result;
    }

    /**
     * Updates standard version in database by inserting new, returns id if success, error otherwise
     * @param $standard_version
     * @return DBError|null|string
     */
    public function update($standard_version)
    {
        if(!$this->isValidId($standard_version->getId(), "standard_version")) {
            return new DBError("Invalid id");
        }
        $result = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.standard_version
                VALUES (?, now(), ?, ?, ?);";
        $parameters = array(
            $standard_version->getId(),
            $standard_version->getStandardId(),
            $standard_version->getDocumentVersionId(),
            $standard_version->getComment());
        try {
            if($this->queryDB($sql, $parameters)) {
                $result = $this->connection->lastInsertId();  // Sets id of the updated standard version
            }
        } catch(PDOException $e) {
            $result = new DBError($e);     // Sets DBError
        }
        return $result;
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