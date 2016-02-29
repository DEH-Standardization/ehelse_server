<?php
require_once 'DBMapper.php';

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
     * @return DBError|null|Standard
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
            if ($result->rowCount() == 1) {
                $row = $result->fetch();
                return new Standard(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']);
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
     * @return array|DBError|null
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
     * @return mixed
     */
    public function getStandardVersionByStandard($standard)
    {
        return $this->getStandardVersionByStandardIdDB($standard->getId());
    }

    /*
     * Returns topic based on standard id
     */
    public function getTopicByStandardId($id)
    {
        if(!$this->isValidId($id, "standard")) {
            return new DBError("Invalid id");
        }
        return $this->getTopicByStandard($this->getStandardById($id));
    }

    /**
     * Returns topic
     * @param $standard
     * @return DBError|null|Topic
     */
    public function getTopicByStandard($standard)
    {
        if(!$this->isValidId($standard->getId(), "standard")) {
            return new DBError("Standard has invalid id");
        }
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
       /* $sql = "select T.id, T.timestamp, T.title, T.description, T.number, T.is_in_catalog, T.sequence, T.parent_id
                from $dbName.standard as S, $dbName.topic as T
                where S.id = ? and T.id = S.topic_id;"; */
        $sql = "SELECT *
                FROM andrkje_ehelse_db.topic WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM andrkje_ehelse_db.topic
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
                    $row['number'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['parent_id']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " standards, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;


    }

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
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']));
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

    public function getAllLoggedStandardsByStandard($standard)
    {
        return $this->getAllLoggedStandardsByStandardId($standard->getId());
    }

    /**
     * Adds new standard to database
     * @param $standard
     * @return DBError|null|string
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
            $standard->getIsInCatalog(),
            $standard->getSequence(),
            $standard->getTopicId(),
        );
        try {
            $this->queryDB($sql, $parameters);
            $response = "success";
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Updates standard to database
     * @param $standard
     * @return DBError|null|string
     */
    public function update($standard)
    {
        if(!$this->isValidId($standard->getId(), "standard")) {
            return new DBError("Invalid id");
        }

        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        /*$sql = "UPDATE $db_name.standard
                SET id = ?, `timestamp` = now(), title = ?, description = ?, is_in_catalog = ?, sequence = ?, topic_id = ?
                WHERE id = ?;";
        */
        $sql = "INSERT INTO $db_name.standard
                VALUES (?, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $standard->getId(),
            $standard->getTitle(),
            $standard->getDescription(),
            $standard->getIsInCatalog(),
            $standard->getSequence(),
            $standard->getTopicId()
        );
        try {
            if($this->queryDB($sql, $parameters)) {
                print_r($parameters);
                $response = "success";
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}