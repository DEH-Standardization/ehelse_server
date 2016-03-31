<?php


require_once 'LinkCategoryDBMapper.php';
require_once __DIR__.'/../models/LinkCategory.php';


$m = new LinkCategoryDBMapper();

$d = new LinkCategory(7,'test', 'sdffd');
    //new Document(1,null,'test','test',1,1,null,1,1,null,null);
echo($m->update($d));