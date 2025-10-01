<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests;

use Ocolin\Hyconext\API;

class ApiTest extends testTest
{
    public static API $api;

    /*
    public function testMacList() : void
    {
        $output = self::$api->show_Mac_Address();
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'totals', $output );
        self::assertObjectHasProperty( 'list', $output );
        self::assertIsObject( $output->totals );
        self::assertObjectHasProperty( 'Total', $output->totals );
        self::assertIsArray( $output->list );
        self::assertIsObject( $output->list[0] );
    }


    public function testInterface() : void
    {
        $output = self::$api->show_Interface();
        self::assertIsArray( $output );
        self::assertIsObject( $output[0] );
    }
    */

    public function testPoe() : void
    {
        $output = self::$api->show_POE();
        self::assertIsArray( $output );
        self::assertIsObject( $output[0] );
    }

    public static function setUpBeforeClass() : void
    {
        self::$api = new API(
            host: $_ENV['TEST_IP_ADDRESS'],
            pass: $_ENV['TEST_PASSWORD']
        );
    }
}