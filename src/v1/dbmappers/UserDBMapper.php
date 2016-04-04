<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../dbmappers/DBMapper.php';

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
            $this->queryDBWithAssociativeArray(User::SQL_INSERT_STATEMENT, $user->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function update($user)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(User::SQL_UPDATE_STATEMENT, $user->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
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
            $response = $objects;

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

    public function resetPassword($user)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(User::SQL_UPDATE_PASSWORD_STATEMENT, $user->toResetPasswordDBArray());
            $response = $this->connection->lastInsertId();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getByEmail($email)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(User::SQL_GET_USER_BY_EMAIL, array(":email" => $email));
            $response = $result->fetch();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function get($model)
    {
        // TODO: Implement get() method.
        throw new Exception("Not implemented error");
    }

    public function delete($model)
    {
        /*// TODO: Implement delete() method.
        throw new Exception("Not implemented error");*/
    }

    public function deleteById($id)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(User::SQL_DELETE_USER_BY_ID,array(":id"=>$id));
            $response = array();
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }
}