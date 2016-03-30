<?php


class EHelseDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
// only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    protected $file;

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

    protected function setUp(){
        parent::setUp();
        $this->db = null;
    }

    public function getDataSet(){
        return $this->createFlatXmlDataSet(dirname(__FILE__) . $this->file);
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

}
