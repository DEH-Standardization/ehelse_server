<?php

class StandardVersion
{
    private $id, $timestamp, $standard_id, $document_id, $document_version_id;

    public  function __construct($id, $timestamp, $standard_id, $document_id, $document_version_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->standard_id = $standard_id;
        $this->document_id = $document_id;
        $this->document_version_id = $document_version_id;
    }

    public static function getStandardVersionsByStandardId($id)
    {
        $standard_versions = array();

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = "select SV.id, SV.standard_id, SV.document_version_id
                    from andrkje_ehelse_db.standard_version as SV, andrkje_ehelse_db.standard as S
                    where SV.standard_id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                array_push($standard_versions, new StandardVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['standard_id'],
                    $row['document_id'],
                    $row['document_version_id']));
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $standard_versions;
    }

    public static function getStandardVersionByStandardVersionId($id){
        $standard_version = NULL;

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = "select * from andrkje_ehelse_db.standard_version where id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            if ($result->rowCount() == 1){
                $row = $result->fetch();
                $standard_version = new StandardVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['standard_id'],
                    $row['document_id'],
                    $row['document_version_id']);
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $standard_version;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getStandardId()
    {
        return $this->standard_id;
    }

    public function setStandardId($standard_id)
    {
        $this->standard_id = $standard_id;
    }

    public function getDocumentVersion()
    {
        return $this->document_version;
    }

    public function setDocumentVersion($document_version)
    {
        $this->document_version = $document_version;
    }

}