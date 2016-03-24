<?php

interface iModel
{
    public function toArray();  // Returns associative array representation of model.
    public function toJSON();   // Returns JSON representation of model.
}