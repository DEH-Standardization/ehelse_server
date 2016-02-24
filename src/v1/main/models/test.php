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

$s1 = new Standard(3 ,null,"Change test  5","555",1,true,3);
print_r($sm->getTopicByStandard($s1));


/*
$s2 = new Standard(3 ,null,"Change 1","sdfgfdfgfd",1,true,2);
$sm->update($s2);
sleep(2);

$s3 = new Standard(3 ,null,"Change 2","sdkjfsfeijiojewjioioioweijoejwioef",1,true,2);
$sm->update($s3);
*/

//print_r($sm->getStandardVersionByStandardId(2));

