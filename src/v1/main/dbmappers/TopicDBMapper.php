<?php
require_once "DBMapper.php";
require_once "../models/Topic.php";
require_once "../DBError.php";
/**
 *
 */
class TopicDbMapper extends DBMapper
{
    /**
     * TopicDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns topic based on topic id
     * @param $id
     * @return null|Topic
     */
    public function getTopicById($id)
    {
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $result = $this->queryDB("select * from $dbName.topic where id = ?;", array($id));
        if ($result->rowCount() == 1) {
            $row = $result->fetch();
            return new Topic(
                $row['id'],
                $row['timestamp'],
                $row['title'],
                $row['description'],
                $row['sequence'],
                $row['is_in_catalog'],
                $row['sequence'],
                $row['parent_id']);
        } else {
            trigger_error($result->errorInfo(), E_USER_ERROR);
        }
        return null;
    }

    public function getStandardsByTopicId($id)
    {
        $response = null;
        $standards = array();
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $db_name.standard
                where topic_id = ?;";

        try {
            $result = $this->queryDB($sql, array($id));
            foreach($result as $row) {
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

    public function getStandardsByTopic($topic)
    {
        return $this->getSubtopicsByTopicId($topic->getId());
    }

    /**
     * Returns a list of subtopics to a topic from it's id
     * @param $id
     * @return array|DBError|null
     */
    public function getSubtopicsByTopicId($id)
    {
        $response = null;
        $topics = array();
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $db_name.topic where parent_id = ?";

        try {
            $result = $this->queryDB($sql, array($id));
            foreach($result as $row) {
                array_push($topics, new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['number'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['parent_id']));
            }
            if (count($topics) == 0) {
                $response = new DBError("Did not return any results on id: ".$id);
            } else {
                return $topics;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Adds new topic to database
     * @param $topic
     * @return DBError|null|string
     */
    public function add($topic)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.topic
                VALUES (null, now(), ?, ?, ?, ?, ?, ?);";
        $parameters = array(
            $topic->getTitle(),
            $topic->getDescription(),
            $topic->getNumber(),
            $topic->getIsInCatalog(),
            $topic->getSequence(),
            $topic->getParentId(),
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
     * Updates topic in database
     * @param $topic
     * @return DBError|null|string
     */
    public function update($topic)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "UPDATE $db_name.topic
                SET `timestamp` = now(), title = ?, description = ?, number = ?, is_in_catalog = ?, sequence = ?, parent_id = ?
                WHERE id = ?;";
        $parameters = array(
            $topic->getTitle(),
            $topic->getDescription(),
            $topic->getNumber(),
            $topic->getIsInCatalog(),
            $topic->getSequence(),
            $topic->getParentId(),
            $topic->getId()
            );
        try {
            $this->queryDB($sql, $parameters);
            $response = "success";
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }





}