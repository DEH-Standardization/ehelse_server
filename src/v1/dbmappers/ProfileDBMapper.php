<?php
require_once 'DBMapper.php';
require_once __DIR__.'/../models/Profile.php';
require_once __DIR__.'/../errors/DBError.php';

/**
 *
 */
class ProfileDBMapper extends DBMapper
{
    /**
     * ProfileDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns profile based on id
     * @param $id
     * @return DBError|Profile
     */
    public function getProfileById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.profile WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM andrkje_ehelse_db.profile
                GROUP BY id)";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);

            if ($result->rowCount() === 1) {
                $row = $result->fetch();
                return new Profile(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                " profiles, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;

    }

    /**
     * Returns list of profile versions based on id
     * @param $id
     * @return array|DBError
     */
    public function getProfileVersionByProfileId($id)
    {
        $response = null;
        $profile_versions = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.profile_version where profile_id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            foreach ($result as $row) {
                array_push($profile_versions, new ProfileVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['profile_id'],
                    $row['document_id'],
                    $row['document_version_id']));
            }
            if (count($profile_versions) == 0) {
                $response = new DBError("Did not return any results on id: ".$id);
            } else {
                return $profile_versions;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns list of profile versions
     * @param $profile
     * @return array|DBError
     */
    public function getProfileVersionByProfile($profile)
    {
        return $this->getProfileVersionByProfileIdDB($profile->getId());
    }

    /**
     * Returns topic based on profile id
     * @param $id
     * @return DBError|Topic
     */
    public function getTopicByProfileId($id)
    {
        if(!$this->isValidId($id, "profile")) {
            return new DBError("Invalid id");
        }
        return $this->getTopicByProfile($this->getProfileById($id));
    }

    /**
     * Returns the topic the profile belongs to
     * @param $profile
     * @return DBError|Topic
     */
    public function getTopicByProfile($profile)
    {
        if(!$this->isValidId($profile->getId(), "profile")) {
            return new DBError("Profile has invalid id");
        }
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.topic WHERE id = ? and (id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.topic
                  GROUP BY id)";
        $parameters = array($profile->getId());
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
                    $row['sequence'],
                    $row['parent_id']);
            } else {
                $response = new DBError("Returned " . $result->rowCount() .
                    " profiles, expected 1");
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns the newest logged versions of all profiles
     * @return array|DBError
     */
    public function getAllIds()
    {
        $response = null;
        $profile_versions = array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "SELECT *
                FROM $dbName.profile WHERE(id,timestamp) IN
                ( SELECT id, MAX(timestamp)
                  FROM $dbName.profile
                  GROUP BY id);";
        try {
            $result = $this->queryDB($sql, array());
            foreach ($result as $row) {
                array_push($profile_versions, new Profile(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']));
            }
            if (count($profile_versions) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                return $profile_versions;
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
    public function getAllLoggedProfilesByProfileId($id)
    {
        $response = null;
        $profiles= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.profile where id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            foreach ($result as $row) {
                array_push($profiles, new Profile(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['sequence'],
                    $row['topic_id']));
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

    }

    /**
     * Returns all logged profiles
     *
     * Returns all logged version profiles, not just the newest.
     *
     * @param $profile
     * @return array|DBError
     */
    public function getAllLoggedProfilesByProfile($profile)
    {
        return $this->getAllLoggedProfilesByProfileId($profile->getId());
    }

    /**
     * Adds new profile to database
     * @param $profile
     * @return DBError|string
     */
    public function add($profile)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.profile
                VALUES (null, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $profile->getTitle(),
            $profile->getDescription(),
            $profile->getIsInCatalog(),
            $profile->getSequence(),
            $profile->getTopicId(),
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
     * Updates profile to database
     * @param $profile
     * @return DBError|string
     */
    public function update($profile)
    {
        if(!$this->isValidId($profile->getId(), "profile")) {
            return new DBError("Invalid id");
        }
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "INSERT INTO $db_name.profile
                VALUES (?, now(), ?, ?, ?, ?, ?);";
        $parameters = array(
            $profile->getId(),
            $profile->getTitle(),
            $profile->getDescription(),
            $profile->getIsInCatalog(),
            $profile->getSequence(),
            $profile->getTopicId()
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

}