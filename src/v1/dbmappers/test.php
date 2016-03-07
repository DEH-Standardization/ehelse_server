<?php


require_once 'ActionDBMapper.php';
require_once __DIR__.'/../models/Action.php';


$tm = new ActionDBMapper();

//$id = ($tm->add(new Action(3, 'Spise middag', 'Det er vel en god nok action?')));
echo "_____________- <br><br>";

//print_r($tm->getById($id));

echo "_____________- <br><br>";

print_r($tm->update(new Action(3, 'Spise middag', 'Taco plz!')));

echo "_____________- <br><br>";

print_r($tm->getAll());

