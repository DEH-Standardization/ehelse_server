<?php


class ProfileVersion
{
    // TODO: Remove $is_in_catalog
    // TODO: Add $comment
    private $id, $timestamp, $profile_id, $standard_version_id, $document_version_id;

    public function __construct($id, $time_stamps, $profile_id, $standard_version_id, $document_version_id)
    {
        $this->id = $id;
        $this->timestamp = $time_stamps;
        $this->standard_version_id = $standard_version_id;
        $this->profile_id = $profile_id;
        $this->document_version_id = $document_version_id;
    }

    public static function getProfileVersionsByProfileId($id)
    {
        $profile_version = array();

        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = "select PV.id, PV.timestamp, PV.profile_id, PV.standard_version_id, PV.document_version_id
from andrkje_ehelse_db.profile_version as PV
where PV.profile_id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                array_push($profile_version, new ProfileVersion(
                    $row['id'],
                    $row['timestamp'],
                    $row['profileId'],
                    $row['standardVersionId'],
                    $row['documentVersionId']));
            }
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $profile_version;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTimeStamps()
    {
        return $this->timestamp;
    }

    public function setTimeStamps($time_stamps)
    {
        $this->timestamp = $time_stamps;
    }

    public function getDocumentVersionId()
    {
        return $this->document_version_id;
    }

    public function setDocumentVersionId($document_version_id)
    {
        $this->document_version_id = $document_version_id;
    }

    public function getStandardVersionId()
    {
        return $this->standard_version_id;
    }

    public function setStandardVersionId($standard_version_id)
    {
        $this->standard_version_id = $standard_version_id;
    }

    public function getProfileId()
    {
        return $this->profile_id;
    }

    public function setProfileId($profile_id)
    {
        $this->profile_id = $profile_id;
    }
}