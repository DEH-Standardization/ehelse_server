<?php


class ProfileVersion
{
    private $id, $time_stamps, $profile_id, $standard_version_id, $document_version_id;

    public function __construct($id, $time_stamps, $profile_id, $standard_version_id, $document_version_id)
    {
        $this->id = $id;
        $this->time_stamps = $time_stamps;
        $this->standard_version_id = $standard_version_id;
        $this->profile_id = $profile_id;
        $this->document_version_id = $document_version_id;
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
        return $this->time_stamps;
    }

    public function setTimeStamps($time_stamps)
    {
        $this->time_stamps = $time_stamps;
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