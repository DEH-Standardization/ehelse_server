<?php

interface iModel
{
    public function toArray();  // Returns associative array representation of model

    public function toJSON();   // Returns JSON representation of model

    public static function fromDBArray($db_array);  // Creates model from a DB array

    public static function fromJSON($json); // Creates model from JSON

    public function toDBArray();    // Returns DB array representation of model
}