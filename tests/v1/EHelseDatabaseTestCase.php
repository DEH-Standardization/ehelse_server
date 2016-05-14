<?php
require_once __DIR__ . '/../../src/v1/responses/Response.php';

abstract class EHelseDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
// only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;
    protected $databaseTester;
    const list_name = "";
    const fields = [];

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    protected $file;

    protected static function isCorrectResponseData($body, $topic_data)
    {
        $is_correct_data = true;
        $json = json_decode($body, true);
        foreach($topic_data as $key => $value){
            if(!array_key_exists($key, $json) || $json[$key] != $value){
                $is_correct_data = false;
                break;
            }
        }
        return $is_correct_data;
    }

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( "mysql:dbname=".DB_NAME.";host=".DB_SERVER, DB_USERNAME, DB_PASSWORD );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, "db");
        }

        return $this->conn;
    }
    private $db;

    protected function mySetup($dataSet){
        PHPUnit_Framework_TestCase::SetUp();

        $this->databaseTester = NULL;

        $this->getDatabaseTester()->setSetUpOperation(
            $this->getSetUpOperation()
        );

        $this->getDatabaseTester()->setDataSet(
            $this->createFlatXmlDataSet($dataSet));
        $this->getDatabaseTester()->onSetUp();
    }

    protected function getTearDownOperation() {
        return PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL();
    }

    public function getDataSet(){
        return $this->createFlatXmlDataSet(dirname(__FILE__) . "/empty.xml");
    }

    public static function assertEquals($a, $b){
        return parent::assertEquals($a,$b, debug_backtrace()[1]['function']);

    }
    public static function assertRegExp($a, $b){
        return parent::assertRegExp($a,$b, debug_backtrace()[1]['function']);

    }
    public static function assertTrue($a){
        return parent::assertTrue($a, debug_backtrace()[1]['function']);
    }

    public static function assertIsValidResponse(Response $response, $status_code, $class = Response::class)
    {
        return self::assertTrue(
            self::isValidResponse($response, $status_code, $class = Response::class)
        );
    }

    public static function assertIsValidErrorResponse(ErrorResponse $response, $status_code)
    {
        return self::assertTrue(
            self::isValidErrorResponse($response, $status_code)
        );
    }


    protected static function assertIsCorrectResponseData($body, $topic_data)
    {
        return self::assertTrue(
            self::isCorrectResponseData($body, $topic_data)
        );
    }


    public static function isValidJSONList($json)
    {

        $class = get_called_class();
        if(!is_array($json)){
            $json = json_decode($json, true);
        }
        $valid = false;
        if(array_key_exists($class::list_name, $json)){
            $valid = true;
            foreach($json[$class::list_name] as $element){
                if(!self::isValidJSON($element, $class::fields)){
                    $valid = false;
                    break;
                }
            }
        }
        return $valid;
    }

    protected static function isValidJSON($json, $keys)
    {

        $class = get_called_class();
        if(!is_array($json)){
            $json = json_decode($json, true);
        }
        $valid = true;
        foreach($keys as $key){
            if(!array_key_exists($key, $json)){
                $valid = false;
                break;
            }
        }
        return $valid;
    }


    protected static function isValidResponse(Response $response, $status_code, $class = Response::class)
    {
        $called_class = get_called_class();
        return get_class($response) == $class &&
        $response->getResponseCode() == $status_code &&
        self::isValidJSON($response->getBody(), $called_class::fields);
    }

    protected static function assertIsValidResponseList(Response $response, $status_code, $class = Response::class)
    {
        return get_class($response) == $class &&
        $response->getResponseCode() == $status_code &&
        self::isValidJSONList($response->getBody());
    }

    protected static function isValidErrorResponse(ErrorResponse $response, $status_code)
    {
         self::isValidResponse($response, $status_code, ErrorResponse::class);

        return get_class($response) == ErrorResponse::class &&
        $response->getResponseCode() == $status_code &&
        self::isValidErrorResponseJSON($response->getBody());
    }

    protected static function isValidErrorResponseJSON($body)
    {
        $json = json_decode($body, true);
        return self::isValidJSON($json, ['title', 'message']);
    }


}
