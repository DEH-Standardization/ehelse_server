<?php
function trimPath($path, $number){
    for($i = 0; $i < $number; $i++){
        unset($path[$i]);
    }
    return array_values($path);
}

function isAssoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1) && count($arr);
}