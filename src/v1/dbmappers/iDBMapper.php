<?php

/**
 *
 */
interface iDBMapper
{
    public function add($model);        // Add element to DB.
    public function update($model);     // Update element in DB.
    //public function get($model);        // Get element by model (picks id) from DB.
    //public function getById($id);       // Get element by id from DB.
    public function getAll();           // Return all (newest version) of element form DB.
    //public function getAllIds();        // Return all ids (newest version) of element form DB.
}