<?php

require_once 'pw.php';

class DbCommunication
{
    const DATABASE_NAME = "ehelse_test";

    private $connection;
    private static $instance;
    private $server = "refkat.eu";
    private $username = "ehelse_admin";
    private $password;

    /**
     * Returns an instance of the database communication.
     * @return DbCommunication
     */
    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * DbCommunication constructor.
     */
    private function __construct()
    {
        $this->password = DB_PASSWORD;
        try {
            $this->connection = new PDO("mysql:host=$this->server;dbname=".DBCommunication::DATABASE_NAME.";charset=utf8", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
        }
        catch(PDOException $e)
        {
            trigger_error("Failed to connect to MySQL: " . $e , E_USER_ERROR);
        }
    }

    /**
     * Returns connection to the database.
     *
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Overrides the Clone function to prevent multiple instances of DbCommunication
     */
    function __clone()
    {
    }


}