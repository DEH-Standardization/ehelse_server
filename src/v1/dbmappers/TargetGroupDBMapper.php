<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/TargetGroup.php';
require_once __DIR__.'/../errors/DBError.php';

class TargetGroupDBMapper extends DBMapper
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'TargetGroup';
    }

    public function get($target_group)
    {
        $this->getById($target_group->getId());
    }

    public function getById($id)
    {
        $response = null;
        $sql = "SELECT * FROM target_group WHERE id = ?;";
        $parameters = array($id);
        try {
            $result = $this->queryDB($sql, $parameters);
            $row = $result->fetch();
            if ($row) {
                $response = TargetGroup::fromDBArray($row);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        $response = null;
        $target_groups= array();
        $dbName = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $dbName.target_group";
        try {
            $result = $this->queryDB($sql, null);
            foreach ($result as $row) {
                array_push($target_groups, TargetGroup::fromDBArray($row));
            }
            $response = $target_groups;

        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function add($target_group)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();

        try {
            $this->queryDBWithAssociativeArray(TargetGroup::SQL_INSERT_STATEMENT, $target_group->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($target_group)
    {
        $response = null;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        try {
            $this->queryDBWithAssociativeArray(TargetGroup::SQL_UPDATE_STATEMENT, $target_group->toDBArray());
            return $target_group->getId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }


    public function getTargetGroupAsThree()
    {
        $target_group_three = [];
        $target_group_dict = array();
        $target_group_children = array();
        $target_group_list = $this->getAll();
        foreach ($target_group_list as $target_group) {
            $parent_id = $target_group->getParentId();
            if ($parent_id == null) {
                array_push($target_group_three, $target_group);
            } else {
                if (!array_key_exists($parent_id, $target_group_children)) {
                    $target_group_children[$parent_id] = array();
                }
                array_push($target_group_children[$parent_id], $target_group);
            }
            $target_group_dict[$target_group->getID()] = $target_group;
        }
        foreach ($target_group_children as $parent => $children) {
            $target_group_dict[$parent]->addChildren($children);
        }
        return $target_group_three;
    }
    
}