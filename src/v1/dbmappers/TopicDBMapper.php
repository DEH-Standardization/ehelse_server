<?php
require_once "DBMapper.php";
require_once __DIR__. "/../models/Topic.php";
require_once __DIR__. "/../errors/DBError.php";
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
        $sql = "SELECT *
                FROM topic WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM topic
                  GROUP BY id)";
        $result = $this->queryDB($sql, array($id));
        if( $result instanceof DBError){
            return $result;
        }
        else{
            $row = $result->fetch();
            if ($row) {

                return new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['parent_id'],
                    $row['comment']);
            }
        }
        return null;
    }


    /**
     * Returns list of standards based on id
     * @param $id
     * @return array|DBError|null
     */
   /* public function getProfileByTopicId($id)
    {
        $response = null;
        $profiles = array();
        $sql = "SELECT *
                FROM profile WHERE topic_id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM profile
                  GROUP BY id);";

        try {
            $result = $this->queryDB($sql, array($id));
            foreach($result as $row) {
                array_push($profiles, new Profile(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['topic_id'],
                    $row['comment']));
            }
            if (count($profiles) == 0) {
                $response = new DBError("Did not return any results on id: ".$id);
            } else {
                return $profiles;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }*/

    /**
     * Returns list of standards
     * @param $id
     * @return array|DBError|null
     */
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

        $sql = "SELECT *
                FROM topic WHERE parent_id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM topic
                  GROUP BY id);";

        try {
            $result = $this->queryDB($sql, array($id));
            foreach($result as $row) {
                array_push($topics, new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['parent_id'],
                    $row['comment']));
            }
            $response = $topics;
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns list of all earlier logged versions based on id
     * @param $id
     * @return array|DBError|null
     */
    public function getAllLoggedTopicsByTopicId($id)
    {
        $response = null;
        $topics = array();
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $db_name.topic where id = ?";

        try {
            $result = $this->queryDB($sql, array($id));
            foreach($result as $row) {
                array_push($topics, new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['sequence'],
                    $row['parent_id'],
                    $row['comment']));
            }
                $response = $topics;
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns list of all earlier logged versions based on id
     * @param $topic
     * @return array|DBError|null
     */
    public function getAllLoggedTopicsByTopic($topic)
    {
        return getAllLoggedTopicsByTopic($topic->getId());
    }

    /**
     * Adds new topic to database
     * @param $topic
     * @return DBError|null|string
     */
    public function add($topic)
    {
        $response = null;
        $sql = "INSERT INTO topic
                VALUES (null, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $topic->getTitle(),
            $topic->getDescription(),
            $topic->getSequence(),
            $topic->getParentId(),
            $topic->getComment()
        );
        try {
            $result = $this->queryDB($sql, $parameters);
            if($result instanceof DBError){
                $response = $result;
            }
            else{
                $response = $this->connection->lastInsertId();
            }
        } catch(PDOException $e) {
            $response = $e;
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
        if(!$this->isValidId($topic->getId(), "topic")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.topic
                VALUES (?, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $topic->getId(),
            $topic->getTitle(),
            $topic->getDescription(),
            $topic->getSequence(),
            $topic->getParentId(),
            $topic->getComment()
        );
        try {
            $this->queryDB($sql, $parameters);
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }


    public function getAllIds()
    {
        $response = null;
        $topics = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT id
                FROM $dbName.topic";
        try {
            $result = $this->queryDB($sql, array());
            foreach ($result as $row) {
                array_push($topics, $row['id']);
            }
            if (count($topics) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $topics;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        $response = null;
        $sql = "SELECT *
                FROM topic WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM topic
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            $topics_raw = $result->fetchAll();
            $topics = [];
            foreach($topics_raw as $topic_raw){
                array_push($topics, Topic::fromDBArray($topic_raw));
            }
            $response = $topics;
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Deletes topic by id by setting is_archived = 1
     * @param $id
     * @return array|DBError|null
     */
    public function deleteById($id)
    {
        $response = null;
        try {
            $timestamp = $this->queryDBWithAssociativeArray(
                Topic::SQL_GET_MAX_TIMESTAMP, array(':id' => $id)
            )->fetch()[0];  // gets the string representation of timestamp from database
            $this->queryDBWithAssociativeArray(
                Topic::SQL_DELETE,
                array(
                    ':id' => $id,
                    ':timestamp' => $timestamp)
            );
            $response = [];
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;

    }

    /**
     * Return tree representation of topics
     * @return array
     */
    public function getTopicsAsThree()
    {
        $topic_three = [];
        $topic_dict = array();
        $topic_children = array();
        $topic_list = $this->getAll();
        foreach($topic_list as $topic){
            $parent_id = $topic->getParentId();
            if($parent_id == null){
                array_push($topic_three, $topic);
            }
            else{
                if(!array_key_exists($parent_id, $topic_children)){
                    $topic_children[$parent_id] = array();
                }
                array_push($topic_children[$parent_id], $topic);
            }
            $topic_dict[$topic->getID()] = $topic;
        }
        foreach($topic_children as $parent =>  $children){
            $topic_dict[$parent]->addChildren($children);
        }
        return $topic_three;
    }

    /**
     * Returns true if topic with id has any subtopics
     * @param $id
     * @return bool
     */
    public function hasSubtopic($id)
    {
        $has_subtopics = false;
        try {
            $result = $this->queryDBWithAssociativeArray(Topic::SQL_GET_SUBTOPICS, array(':id' => $id));
            if ($result->fetchAll())
                $has_subtopics = true;
            else
                $has_subtopics = false;
        } catch(PDOException $e) {
            printf($e);     // TODO: find a better way to handle this
            $has_subtopics = false; // does this make sense?
        }
        return $has_subtopics;
    }

    /**
     * Returns true if topic with id has any documents
     * @param $id
     * @return bool
     */
    public function hasDocuments($id)
    {
        $has_documents = false;
        try {
            $result = $this->queryDBWithAssociativeArray(Topic::SQL_GET_DOCUMENTS_BY_TOPIC_ID, array(':topic_id' => $id));
            if ($result->fetchAll())
                $has_documents = true;
            else
                $has_documents = false;
        } catch(PDOException $e) {
            printf($e);     // TODO: find a better way to handle this
            $has_documents = false; // does this make sense?
        }
        return $has_documents;
    }
}