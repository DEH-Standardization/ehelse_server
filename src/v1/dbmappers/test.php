<?php


require_once 'DocumentDBMapper.php';
require_once __DIR__.'/../models/Document.php';


$m = new DocumentDBMapper();

$d = new Document(1,null,'test','test',1,1,null,1,1,null,null);
print_r($m->add($d));
