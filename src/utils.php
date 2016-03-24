<?php
function trimPath($path, $number){
    for($i = 0; $i < $number; $i++){
        unset($path[$i]);
    }
    return array_values($path);
}