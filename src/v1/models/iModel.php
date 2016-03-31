<?php

interface iModel
{
    public function toArray();  // Returns associative array representation of model.
    public function toJSON();   // Returns JSON representation of model.
    public static function fromDBArray($db_array);
    public static function fromJSON($json);
    public function toDBArray();
}