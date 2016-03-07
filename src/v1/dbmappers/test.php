<?php


require_once 'DocumentVersionDBMapper.php';
require_once __DIR__.'/../models/DocumentVersion.php';


$tm = new DocumentVersionDBMapper();

/*
$id = ($tm->add(new DocumentVersion(1, 'doc ver 2', 1)));
echo "_____________- <br><br>";

//print_r($id);

echo "_____________- <br><br>";

*/

print_r($tm->update(new DocumentVersion(3, 'three', 1)));

echo "_____________- <br><br>";

print_r($tm->getAll());

