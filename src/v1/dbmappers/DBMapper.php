<?php
require_once "iDBMapper.php";
require_once "DbCommunication.php";

/**
 * Class DBMapper
 */
abstract class DBMapper implements iDbMapper
{
    protected $connection, $model;

    /**
     * TopicDbMapper constructor.
     */
    public function __construct()
    {
        $this->connection = DbCommunication::getInstance()->getConnection();
    }

    /**
     * Sending query to database
     *
     * Method is sending a SQL query to the database, and a list of parameters
     *  used in the query. The result from database is returned.
     * @param $sql
     * @param $columns
     * @return PDOStatement
     */
    protected function queryDB($sql, $columns)
    {
        if(isAssoc($columns)){
            throw new Exception("Assosiative array. Use other query");
        }
        $sql_query = $sql;
        $stmt = $this->connection->prepare($sql_query);
        if(!$stmt) {
            trigger_error("Could not prepare the SQL query: " . $sql_query . ", " . $this->connection->errorInfo(), E_USER_ERROR);
        }

        for ($i = 0; $i < count($columns); $i++) {
            $stmt->bindParam(($i+1), $columns[$i]);
        }
        try{

            $stmt->execute();
        }
        catch(Exception $e){
            echo "\n\nDBMapper failed!";
            print_r($e);
        }

        return $stmt;
    }

    protected function queryDBWithAssociativeArray($sql, $associative_array)
    {
        $stmt = $this->connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if(!$stmt) {
            trigger_error("Could not prepare the SQL query: " . $sql . ", " . $this->connection->errorInfo(), E_USER_ERROR);
        }

        $stmt->execute($associative_array);

        return $stmt;
    }

    protected function isValidId($id, $table_name)
    {
        $valid = false;
        $db_name = DbCommunication::getInstance()->getDatabaseName();
        $sql = "select * from $db_name.$table_name where id = ?";
        try {
            $result = $this->queryDB($sql, array($id));
            $r = $result->fetch();
            if ($result->rowCount() > 0){
                $valid = true;
            }
        } catch(PDOException $e) {
            print_r($e);
            echo new DBError($e);
        }
        return $valid;
    }


    public function getById($id)
    {
        $response = null;
        $parameters = array(
            'id' => $id);
        $model = $this->model;
        try {
            $result = $this->queryDBWithAssociativeArray($model::SQL_GET_BY_ID, $parameters);
            $raw = $result->fetch();
            if($raw){
                $response = $model::fromDBArray($raw);
            }
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    public function getAll()
    {
        $response = null;
        $array= array();
        $model = $this->model;
        try {
            $result = $this->queryDBWithAssociativeArray($model::SQL_GET_ALL, array());
            foreach ($result as $row) {
                array_push($array, $model::fromDBArray($row));
            }
            $response = $array;
        } catch(PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}