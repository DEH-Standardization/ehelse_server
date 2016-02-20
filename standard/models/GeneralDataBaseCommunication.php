<?php

/**
 * Created by PhpStorm.
 * User: AK
 * Date: 19.02.2016
 * Time: 16.20
 */
class GeneralDataBaseCommunication
{
    public static function getColumnById($table, $id)
    {
        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sql = " SELECT * from andrkje_ehelse_db." . $table ." where parent_id = " . $id . ";";
            $result = $conn->prepare($sql);
            $result->execute();
            return $result;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getRowByID($table, $id)
    {
        $servername = "mysql.stud.ntnu.no";
        $username = "andrkje_ehelse";
        $password = "ehelse12";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=andrkje_ehelse_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
            $sqlQuery = "select * from andrkje_ehelse_db." . $table . " where id =" . $id . ";";

            $result = $conn->prepare($sqlQuery);
            $result->execute();
            if ($result->rowCount() == 1) {
                return $result->fetch();
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return null;
    }
}