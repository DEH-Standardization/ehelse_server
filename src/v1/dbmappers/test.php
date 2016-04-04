<?php


require_once 'LinkCategoryDBMapper.php';
require_once __DIR__.'/../models/LinkCategory.php';


$m = new LinkCategoryDBMapper();
$d = new LinkCategory(8,'oOoOoOoO', 'oOoOoOoO');

print_r($m->add($d));