<?php
require_once __DIR__ . '/StandardVersion.php';
require_once '../dbmappers/DBCommunication.php';

class Standard
{
    private $id, $timestamp, $title, $description, $topic_id, $is_in_catalog;

    public function __construct($id, $timestamp, $title, $description, $topic_id, $is_in_catalog)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->topic_id = $topic_id;
        $this->is_in_catalog = $is_in_catalog;
        $this->title = $title;
        $this->description = $description;
        if (strlen($title) > ModelValidation::getTitleMaxLength()) {
            $this->title = ModelValidation::getValidTitle($title);
            echo "Title is too long. Title set to: " . $this->title;
        } else {
            $this->title = $title;
        }
        if (strlen($description) > ModelValidation::getDescriptionMaxLength()) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "description is too long. Description set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    public function getStandardVersions()
    {
        StandardVersion::getStandardVersionsByStandardId($this->id);
    }

    public static function getStandardByStandardId($id){
        $standard = NULL;

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = DbCommunication::getInstance()->getConnection();
            $sql = "select * from andrkje_ehelse_db.standard where id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            if ($result->rowCount() == 1){
                $row = $result->fetch();
                $standard = new Standard(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['is_in_catalog'],
                    $row['order'],
                    $row['topic_id']);
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $standard;
    }

    public function getId()
    {
        return $this->id;
    }
public function  aa(){}
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function  setTitle($title)
    {
        if (strlen($title) > ModelValidation::getTitleMaxLength()) {
            $this->title = ModelValidation::getValidTitle($title);
            echo "Title is too long. Title set to: " . $this->title;
        } else {
            $this->title = $title;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        if (strlen($description) > ModelValidation::getDescriptionMaxLength($description)) {
            $this->description = ModelValidation::getValidDescription($description);
            echo "description is too long. Description set to: " . $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function getIsInCatalog()
    {
        return $this->is_in_catalog;
    }

    public function setIsInCatalog($is_in_catalog)
    {
        $this->is_in_catalog = $is_in_catalog;
    }


}