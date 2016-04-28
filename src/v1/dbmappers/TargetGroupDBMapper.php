<?php

require_once 'DBMapper.php';
require_once 'DbCommunication.php';
require_once __DIR__.'/../models/TargetGroup.php';
require_once __DIR__.'/../errors/DBError.php';

class TargetGroupDBMapper extends DBMapper
{
    /**
     * TargetGroupDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'TargetGroup';
    }

    /**
     * Returns tree structure of target groups
     * @return array
     */
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