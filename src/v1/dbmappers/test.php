<?php


require_once 'LinkCategoryDBMapper.php';
require_once __DIR__.'/../models/LinkCategory.php';


$m = new LinkCategoryDBMapper();

$d = new LinkCategory(1,'test', 'test11');
print_r($m->getAll());
