<?php
require_once __DIR__ . '/../db_info.php';

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
