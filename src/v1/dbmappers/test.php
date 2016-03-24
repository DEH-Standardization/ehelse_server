<?php


require_once 'StatusDBMapper.php';
require_once __DIR__.'/../models/Status.php';


$m = new StatusDBMapper();

$d = new Status(2, 'test2', 'test');
print_r($m->update($d));
