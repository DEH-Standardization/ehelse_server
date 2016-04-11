<?php


require_once 'LinkDBMapper.php';
require_once __DIR__.'/../models/Link.php';


$m = new LinkDBMapper();
//$d = new LinkCategory(8,'oOoOoOoO', 'oOoOoOoO');

print_r($m->getLinksByDocumentIdAndLinkCategoryId(1,10));

echo ($m->getLinksByDocumentIdAndLinkCategoryId(1,10)[0]->toJSON());