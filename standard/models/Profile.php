<?php


class Profile
{
    private $id, $timestamp, $title, $description, $order, $topic_id;

    public function __construct($id, $timestamp, $title, $description, $order, $topic_id)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->title = $title;
        $this->description = $description;
        $this->order = $order;
        $this->topic_id = $topic_id;
    }

    public function getPrilfeVersions()
    {
        $column = GeneralDataBaseCommunication::getColumnById()
    }

    public function getProfileVersionByProfileId($id)
    {
        $row = GeneralDataBaseCommunication::getRowByID("profile", $id);
        return new Profile(
            row['id'],
            row['timestamp'],
            row['title'],
            row['description'],
            row['order'],
            row['topic_id']);
    }

    public function getId()
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
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

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getTopicId()
    {
        return $this->topic_id;
    }

    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }




}