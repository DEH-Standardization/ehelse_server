<?php
/**
 * Removes n elements from path array
 * @param $path
 * @param $number
 * @return array
 */
function trimPath($path, $number)
{
    for ($i = 0; $i < $number; $i++) {
        unset($path[$i]);
    }
    return array_values($path);
}

/**
 * Returns if an array is an associative array
 * @param $array
 * @return bool
 */
function isAssoc($array)
{
    return array_keys($array) !== range(0, count($array) - 1) && count($array);
}

/**
 * Returns value from array key or null, of the array key does not exist
 * @param $array
 * @param $key
 * @return null
 */
function getValueFromArray($array, $key)
{
    if (array_key_exists($key, $array)) {
        return $array[$key];
    } else {
        return null;
    }
}