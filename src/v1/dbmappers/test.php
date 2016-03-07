<?php


require_once 'LinkTypeDBMapper.php';
require_once __DIR__.'/../models/LinkType.php';


$tm = new LinkTypeDBMapper();

/*
$id = ($tm->add(new LinkType(1, 'Frivillig', 'det som står i navnet...')));
echo "_____________- <br><br>";

print_r($tm->getById($id));

echo "_____________- <br><br>";

*/
print_r($tm->update(new LinkType(3, 'three', '3')));

echo "_____________- <br><br>";

print_r($tm->getAll());

