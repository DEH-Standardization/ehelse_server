<?php


require_once 'LinkDBMapper.php';
require_once __DIR__.'/../models/Link.php';


$m = new LinkDBMapper();

$d = new Link(12,'test','test','url',1,10,'2016-03-30 04:31:06',null);
    //new Document(1,null,'test','test',1,1,null,1,1,null,null);
print_r($m->getLinkCategoriesIdByDocumentId(10));