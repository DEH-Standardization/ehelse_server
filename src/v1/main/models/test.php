<?php
require_once '../dbmappers/TopicDBMapper.php';
require_once 'Standard.php';
require_once '../models/ModelValidation.php';
/**
 * Created by PhpStorm.
 * User: AK
 * Date: 18.02.2016
 * Time: 14.28
 */

/*
$tm = new TopicDbMapper();
$t = $tm->getTopicById(3);

$t->setDescription("idk...");

$t2 = new Topic(3,time(), "test", "test",1,true,1,1);

echo $tm->updateTopic($t2);
*/

$tt = new Topic(1,null,"tt","dd",1,true,1,2);