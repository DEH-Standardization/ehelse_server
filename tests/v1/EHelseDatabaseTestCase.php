<?php
require_once __DIR__ . '/../../src/v1/responses/Response.php';

class EHelseDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
// only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;
    protected $databaseTester;

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

    public static function assertIsValidResponse(Response $response, $status_code, $validation_function, $class = Response::class)
    {
        return self::assertTrue(
            self::isValidResponse($response, $status_code, $validation_function, $class = Response::class)
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


    public static function isValidJSONList($json, $list_name, $validation_function)
    {
        $valid = false;
        if(array_key_exists($list_name, $json)){
            $valid = true;
            foreach($json[$list_name] as $element){
                if(!call_user_func($validation_function, $element)){
                    $valid = false;
                    break;
                }
            }
        }
        return $valid;
    }

    protected static function isValidJSON($json, $keys)
    {
        $valid = true;
        foreach($keys as $key){
            if(!array_key_exists($key, $json)){
                $valid = false;
                break;
            }
        }
        return $valid;
    }


    protected static function isValidResponse(Response $response, $status_code, $validation_function, $class = Response::class)
    {
        return get_class($response) == $class &&
        $response->getResponseCode() == $status_code &&
        call_user_func($validation_function, $response->getBody());
    }

    protected static function isValidErrorResponse(ErrorResponse $response, $status_code)
    {
        return self::isValidResponse($response, $status_code, "EHelseDatabaseTestCase::isValidErrorResponseJSON", ErrorResponse::class);
    }

    protected static function isValidErrorResponseJSON($body)
    {
        $json = json_decode($body, true);
        return self::isValidJSON($json, ['title', 'message']);
    }


}
