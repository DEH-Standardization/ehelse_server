a<?php

require_once 'db_info.php';

class DBCommunication
{

    private static $instance;

    private $connection, $server, $username, $password, $database_name;

    /**
     * Returns an instance of the database communication.
     * @return DBCommunication
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * DBCommunication constructor.
     */
    private function __construct()
    {
        $this->server = DB_SERVER;
        $this->username = DB_USERNAME;
        $this->database_name = DB_NAME;
        $this->password = DB_PASSWORD;
        try {
            $this->connection = new PDO(
                "mysql:host={$this->server};dbname={$this->database_name};charset=utf8",
                $this->username,
                $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
        } catch (PDOException $e) {
            trigger_error("Failed to connect to MySQL: " . $e, E_USER_ERROR);
        }
    }

    /**
     * Returns connection to the database.
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function getDatabaseName()
    {
        return $this->database_name;
    }

    /**
     * Overrides the Clone function to prevent multiple instances of DBCommunication
     */
    function __clone()
    {
    }

}