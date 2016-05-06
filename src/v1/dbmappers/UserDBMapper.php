<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../dbmappers/DBMapper.php';

class UserDBMapper extends DBMapper
{
    /**
     * UserDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = 'User';
    }

    /**
     * Reset password for user
     * @param $user
     * @return DBError|null
     */
    public function resetPassword($user)
    {
        $response = null;
        try {
            $this->queryDBWithAssociativeArray(User::SQL_UPDATE_PASSWORD_STATEMENT, $user->toResetPasswordDBArray());
            $response = $user->getId();//$this->connection->lastInsertId();
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns user by email
     * @param $email
     * @return DBError|mixed|null
     */
    public function getByEmail($email)
    {
        $response = null;
        try {
            $result = $this->queryDBWithAssociativeArray(User::SQL_GET_USER_BY_EMAIL, array(":email" => $email));
            $response = $result->fetch();
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}