<?php


require_once 'DocumentDBMapper.php';
require_once __DIR__.'/../models/Document.php';


$m = new DocumentDBMapper();

$d = new Document(5, null, 'test3_updated','test3',1,1,'d',1,1);
print_r($m->update($d));
