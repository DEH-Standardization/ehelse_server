<?php

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

    public function getStandardById($id)
    {
        $response = null;
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.standard where id = ?;";
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

    public function getStandardVersionByStandardId($id)
    {
        return $this->getStandardVersionByStandardIdDB($id);
    }

    public function getStandardVersionByStandard($standard)
    {
        return $this->getStandardVersionByStandardIdDB($standard->getId());
    }

    public function getTopicByStandardId($id)
    {
        // TODO
    }

    public function getTopicByStandard($standard)
    {
        // TODO
    }

    public function add($model)
    {
        // TODO
    }
    public function update($model)
    {
        // TODO
    }

    private function getStandardVersionByStandardIdDB($id)
    {
        // TODO
    }

}