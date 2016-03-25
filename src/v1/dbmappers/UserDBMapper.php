<?php

require_once __DIR__ . '/../models/User.php';

class UserDBMapper extends DBMapper
{

    /**
     * Adds new topic to database
     * @param $user
     * @return DBError|null|string
     */
    public function add($user)
    {
        $response = null;
        try {
            $this->queryDB(User::SQL_INSERT_STATEMENT, $user->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($model)
    {
        // TODO: Implement update() method.
        throw new Exception("Not implemented");
    }

    public function getAll()
    {
        $response = null;
        $sql = "SELECT * FROM user";
        try {
            $result = $this->queryDB($sql, array());
            $raw = $result->fetchAll();
            $objects = [];
            foreach($raw as $raw_item){
                array_push($objects, User::fromDBArray($raw_item));
            }
            if (count($objects) === 0) {
                $response = new DBError("Did not return any results");
            } else {
                $response = $objects;
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getById($id)
    {
        $response = null;
        $sql = "SELECT * FROM user WHERE id = ?";
        try {
            $result = $this->queryDB($sql, array($id));
            $raw = $result->fetch();
            if($raw){
                $response = User::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}