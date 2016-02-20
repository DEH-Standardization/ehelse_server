<?php


class Topic{
    private $id, $timestamp, $title, $description, $number, $is_in_catalog, $order, $parent_id;

    public function __construct($id, $timestamp, $title, $description, $number, $is_in_catalog, $order, $parent_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->title = $title;
        $this->description = $description;
        $this->number = $number;
        $this->is_in_catalog = $is_in_catalog;
        $this->order = $order;
        $this->parent_id = $parent_id;
    }

    public function getSubTopics()
    {
        /*
        $subtopics = array();

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {

            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = " SELECT * from andrkje_ehelse_db.topic
                    where parent_id = ".$this->id.";";
            $result = $conn->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                array_push($subtopics, new Topic(
                    $row['id'],
                    $row['timestamp'],
                    $row['title'],
                    $row['description'],
                    $row['number'],
                    $row['is_in_catalog'],
                    $row['order'],
                    $row['parent_id']));
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $subtopics;
        */
        $subtopics = array();
        $result = $this->getColumnById("topic", $this->id);
        foreach($result as $row) {
            array_push($subtopics, new Topic(
                $row['id'],
                $row['timestamp'],
                $row['title'],
                $row['description'],
                $row['number'],
                $row['is_in_catalog'],
                $row['order'],
                $row['parent_id']));
        }
        return $subtopics;
    }

    public static function getTopicByTopicId($topic_id)
    {
        /*
        $topic = NULL;

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = "select * from andrkje_ehelse_db.topic where id = " . $topic_id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            if ($result->rowCount() == 1){
                $row = $result->fetch();
                $topic = new StandardVersion($row['id'], $row['standard_id'], $row['document_version_id']);
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $topic;
        */
        $row = self::getRowByID("topic", $topic_id);
        return new Topic(
            $row['id'],
            $row['timestamp'],
            $row['title'],
            $row['description'],
            $row['number'],
            $row['is_in_catalog'],
            $row['order'],
            $row['parent_id']);
    }

    private function getColumnById($table, $id)
    {
        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = " SELECT * from andrkje_ehelse_db." . $table ." where parent_id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            return $result;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getRowByID($table, $id)
    {
        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sqlQuery = "select * from andrkje_ehelse_db." .$table ." where id =" .$id . ";";

            $result = $conn->prepare($sqlQuery);
            $result->execute();
            if ($result->rowCount() == 1){
                return $result->fetch();
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return null;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function  getTitle()
    {
        return $this->title;
    }
    public function  setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getIsInCatalog()
    {
        return $this->is_in_catalog;
    }

    public function setIsInCatalog($is_in_catalog)
    {
        $this->is_in_catalog = $is_in_catalog;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }


}