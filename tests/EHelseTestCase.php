<?php


class EHelseTestCase extends PHPUnit_Framework_TestCase
{
    public static function assertEquals($a, $b){
        return parent::assertEquals($a,$b, debug_backtrace()[1]['function']);

    }
}
