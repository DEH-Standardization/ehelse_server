<?php


require_once 'LinkDBMapper.php';
require_once __DIR__.'/../models/Link.php';


$tm = new LinkDBMapper();


echo ($tm->update(new Link(3, 'link 3', 'link 3', 'google.com',1,2)));
echo "_____________- <br><br>";

//print_r($tm->getAll());

echo "_____________- <br><br>";

/*

print_r($tm->update(new DocumentVersion(3, 'three', 1)));

echo "_____________- <br><br>";

print_r($tm->getAll());
*/
