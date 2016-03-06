<?php

require_once 'pw.php';

class DbCommunication
{
    private $connection;
    private static $instance;
    private $server = "refkat.eu";
    private $username = "ehelse8";
    private $password;
    private $database_name = "andrkje_ehelse_db";

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
        $this->password = getPW();
        try {
            $this->connection = new PDO("mysql:host=$this->server;dbname=andrkje_ehelse_db;charset=utf8", $this->username, $this->password);
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
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database_name;
    }

    /**
     * Overrides the Clone function to prevent multiple instances of DbCommunication
     */
    function __clone()
    {
    }


}