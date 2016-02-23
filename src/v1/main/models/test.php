<?php
require_once '../dbmappers/TopicDBMapper.php';
require_once 'Standard.php';
require_once '../models/ModelValidation.php';
require_once '../dbmappers/StandardDBMapper.php';
/**
 * Created by PhpStorm.
 * User: AK
 * Date: 18.02.2016
 * Time: 14.28
 */

$tm = new TopicDbMapper();

/*
$t = $tm->getTopicById(3);

$t->setDescription("idk...");



//echo $tm->updateTopic($t2);
*/
//echo $tm->getStandardsByTopicId(999)[0]->getTitle();

//$t2 = new Topic(3,time(), "test", "test",1,true,1,1);
//echo $tm->update($t2);


$sm = new StandardDBMapper();
$st = new Standard(3 ,null,"new","aayee",1,true,2);
echo "sdf";
print_r($sm->getStandardVersionByStandardId(2));

