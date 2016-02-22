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
     * TopicDbMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns a list of subtopics to a topic from it's id
     * @param $id
     * @return array
     */
    public function getSubtopicsByTopicId($id)
    {
        return $this->getSubtopicsByTopicIdDB($id);
    }

    /**
     * Returns a list of subtopics to a topic
     * @param $topic
     * @return array
     */
    public function getSubtopicsByTopic($topic)
    {
        return $this->getSubtopicsByTopicIdDB($topic->getId());
    }

    public function getTopicById($id)
    {
        $result = $this->queryDB("select * from andrkje_ehelse_db.topic where id = ?;", array($id));
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

    public function storeTopic($topic)
    {

    }

    public function updateTopic($topic)
    {
        $response = null;

        $sql = "UPDATE andrkje_ehelse_db.topic
                SET `timestamp` = now(), title = ?, description = ?, number = ?, is_in_catalog = ?, sequence = ?, parent_id = ?
                WHERE id = ?;";

        $parameters = array(
            //date('Y-m-d G:i:s'),    // timestamp
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


    private function getSubtopicsByTopicIdDB($id)
    {
        $topics = array();
        $sql = "select * from andrkje_ehelse_db.topic where parent_id = ?";

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
        return $topics;
    }




}