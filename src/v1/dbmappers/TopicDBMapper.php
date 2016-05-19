<?php
require_once "DBMapper.php";
require_once __DIR__ . "/../models/Topic.php";
require_once __DIR__ . "/../errors/DBError.php";

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
        $this->model = 'Topic';
    }


    /**
     * Returns a list of subtopics to a topic from it's id
     * @param $id
     * @return array|DBError|null
     */
    public function getSubtopicsByTopicId($id)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(Topic::SQL_GET_SUBTOPICS, array('id' => $id));
            $raw = $result->fetch();
            if ($raw) {
                $response = Topic::fromDBArray($raw);
            }
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
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

        // Loop through all topics
        foreach ($topic_list as $topic) {
            $parent_id = $topic->getParentId();

            if ($parent_id == null) {
                array_push($topic_three, $topic);
            } else {
                // If parent id does not exist in $topic_children, set to children to empty array
                if (!array_key_exists($parent_id, $topic_children)) {
                    $topic_children[$parent_id] = array();
                }
                array_push($topic_children[$parent_id], $topic);
            }
            $topic_dict[$topic->getID()] = $topic;
        }

        // For each parent, add child
        foreach ($topic_children as $parent => $children) {
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
        } catch (PDOException $e) {
            //printf($e);
            $has_subtopics = false; // does this make sense?
        }
        return $has_subtopics;
    }

    /**
     * Returns true if topic with id has any subtopics
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
        } catch (PDOException $e) {
            //printf($e);
            $has_documents = false; // does this make sense?
        }
        return $has_documents;
    }
}