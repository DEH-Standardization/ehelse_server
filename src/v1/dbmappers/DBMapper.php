<?php
require_once "iDBMapper.php";
require_once "DBCommunication.php";

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
        $this->connection = DBCommunication::getInstance()->getConnection();
    }

    /**
     * Sending query to database
     *
     * Method is sending a SQL query to the database, and a list of parameters
     *  used in the query. The result from database is returned.
     * @param $sql
     * @param $associative_array
     * @return PDOStatement
     */
    protected function queryDBWithAssociativeArray($sql, $associative_array)
    {
        $statement = $this->connection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if (!$statement) {
            trigger_error("Could not prepare the SQL query: " . $sql . ", " . $this->connection->errorInfo(), E_USER_ERROR);
        }

        $statement->execute($associative_array);

        return $statement;
    }

    /**
     * Returns element by id
     * @param $id
     * @return DBError|null
     */
    public function getById($id)
    {
        $response = null;
        $parameters = array(
            'id' => $id);
        $model = $this->model;
        try {
            $result = $this->queryDBWithAssociativeArray($model::SQL_GET_BY_ID, $parameters);
            $raw = $result->fetch();
            if ($raw) {
                $response = $model::fromDBArray($raw);
            }
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns all elements
     * @return array|DBError|null
     */
    public function getAll()
    {
        $response = null;
        $array = array();
        $model = $this->model;
        try {
            $result = $this->queryDBWithAssociativeArray($model::SQL_GET_ALL, array());
            foreach ($result as $row) {
                array_push($array, $model::fromDBArray($row));
            }
            $response = $array;
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Add element to do database
     * @param $object
     * @return DBError|null|string
     */
    public function add($object)
    {
        $response = null;
        $model = $this->model;
        try {
            $this->queryDBWithAssociativeArray($model::SQL_INSERT, $object->toDBArray());
            $response = $this->connection->lastInsertId();
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Update element in database
     * @param $object
     * @return DBError|null
     */
    public function update($object)
    {
        $response = null;
        $model = $this->model;
        try {
            $this->queryDBWithAssociativeArray($model::SQL_UPDATE, $object->toDBArray());
            return $object->getId();
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

    /**
     * Returns version of element stored in database
     * @param $object
     */
    public function get($object)
    {
        $this->getById($object->getId());
    }

    /**
     * Delete element from database
     * @param $object
     * @return array|DBError|null
     */
    public function delete($object)
    {
        return $this->deleteById($object->getId());
    }

    /**
     * Delete element in database by id
     * @param $id
     * @return array|DBError|null
     */
    public function deleteById($id)
    {
        $response = null;
        $model = $this->model;
        try {
            $this->queryDBWithAssociativeArray($model::SQL_DELETE, array(":id" => $id));
            $response = array();
        } catch (PDOException $e) {
            $response = new DBError($e);
        }
        return $response;
    }

}