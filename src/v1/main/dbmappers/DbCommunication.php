<?php

class DbCommunication
{
    private $connection;
    private static $instance;
    private $server = "mysql.stud.ntnu.no";
    private $username = "andrkje_ehelse";
    private $password = "ehelse12";

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
        try {
            $this->connection = new PDO("mysql:host=$this->server;dbname=andrkje_ehelse_db", $this->username, $this->password);
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