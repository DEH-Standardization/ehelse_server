<?php
@define('DB_NAME', 'ehelse_test');
require_once __DIR__ . '/../db_info.php';

if(DB_NAME != "ehelse_test"){
    die("Bruk test databasen!");
}


class EHelseTestCase extends PHPUnit_Framework_TestCase
{

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
