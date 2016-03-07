<?php


require_once 'MandatoryDBMapper.php';
require_once __DIR__.'/../models/Mandatory.php';


$tm = new MandatoryDBMapper();

/*
$id = ($tm->add(new Mandatory(1, 'Frivillig', 'det som står i navnet...')));
echo "_____________- <br><br>";

print_r($tm->getById($id));

echo "_____________- <br><br>";
*/
print_r($tm->update(new Mandatory(2, 'Frivillig', 'jajaj')));

echo "_____________- <br><br>";

print_r($tm->getAll());

