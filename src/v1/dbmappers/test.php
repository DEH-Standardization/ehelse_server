<?php


require_once 'TargetGroupDBMapper.php';
require_once __DIR__.'/../models/TargetGroup.php';


$tm = new TargetGroupDBMapper();

print_r($tm->getAll());

