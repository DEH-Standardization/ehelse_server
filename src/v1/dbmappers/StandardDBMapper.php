<?php
require_once 'DBMapper.php';
require_once __DIR__.'/../models/Standard.php';
require_once __DIR__.'/../errors/DBError.php';

/**
 *
 */
class StandardDBMapper extends DBMapper
{
    /**
     * StandardDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns standard based on id
     * @param $id
     * @return DBError|Standard
     */
    public function getStandardById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.standard WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM andrkje_ehelse_db.standard
                GROUP BY id)";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Standard(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
                    $row['comment']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                " standards, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;

    }

    /**
     * Returns list of standard versions based on id
     * @param $id
     * @return array|DBError
     */
    public function getStandardVersionByStandardId($id)
    {
        $response = null;
        $standard_versions = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.standard_version where standard_id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            foreach ($result as $row) {
                array_push($standard_versions, new StandardVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['standard_id'],
                    $row['document_id'],
                    $row['document_version_id']));
            }
            if (count($standard_versions) == 0) {
                $response = new DBError("Did not return any results on id: ".$id);
            } else {
                return $standard_versions;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns list of standard versions
     * @param $standard
     * @return array|DBError
     */
    public function getStandardVersionByStandard($standard)
    {
        return $this->getStandardVersionByStandardIdDB($standard->getId());
    }

    /**
     * Returns topic based on standard id
     * @param $id
     * @return DBError|Topic
     */
    public function getTopicByStandardId($id)
    {
        if(!$this->isValidId($id, "standard")) {
            return new DBError("Invalid id");
        }
        return $this->getTopicByStandard($this->getStandardById($id));
    }

    /**
     * Returns the topic the standard belongs to
     * @param $standard
     * @return DBError|Topic
     */
    public function getTopicByStandard($standard)
    {
        if(!$this->isValidId($standard->getId(), "standard")) {
            return new DBError("Standard has invalid id");
        }
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.topic WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.topic
                  GROUP BY id)";
        $parameters = array($standard->getId());
        try {
            $result = $this->queryDB($sql, $parameters);
            if ($result->rowCount() == 1) {
                $row = $result->fetch();
                return new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['parent_id'],
                    $row['comment']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " standards, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns the newest logged versions of all standards
     * @return array|DBError
     */
    public function getAllIds()
    {
        $response = null;
        $standard_versions = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.standard WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.standard
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            foreach ($result as $row) {
                array_push($standard_versions, new Standard(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
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
     * @param $id
     * @return array|DBError
     */
    public function getAllLoggedStandardsByStandardId($id)
    {
        $response = null;
        $standards= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.standard where id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            foreach ($result as $row) {
                array_push($standards, new Standard(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
                    $row['comment']));
            }
            if (count($standards) == 0) {
                $response = new DBError("Did not return any results on id: ".$id);
            } else {
                return $standards;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;

    }

    /**
     * Returns all logged standards
     *
     * Returns all logged version standards, not just the newest.
     *
     * @param $standard
     * @return array|DBError
     */
    public function getAllLoggedStandardsByStandard($standard)
    {
        return $this->getAllLoggedStandardsByStandardId($standard->getId());
    }

    /**
     * Adds new standard to database
     * @param $standard
     * @return DBError|string
     */
    public function add($standard)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.standard
                VALUES (null, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $standard->getTitle(),
            $standard->getDescription(),
            $standard->getSequence(),
            $standard->getTopicId(),
            $standard->getComment()
        );
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Updates standard to database
     * @param $standard
     * @return DBError|string
     */
    public function update($standard)
    {
        if(!$this->isValidId($standard->getId(), "standard")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.standard
                VALUES (?, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $standard->getId(),
            $standard->getTitle(),
            $standard->getDescription(),
            $standard->getSequence(),
            $standard->getTopicId(),
            $standard->getComment()
        );
        try {
            if($this->queryDB($sql, $parameters)) {
                print_r($parameters);
                $response = "200";// "success";
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }
}