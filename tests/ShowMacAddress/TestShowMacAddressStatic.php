<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests\ShowMacAddress;

use Ocolin\Hyconext\Tests\testTest;
use Ocolin\Hyconext\API;

class TestShowMacAddressStatic extends testTest
{
    public static function testFullRaw() : void
    {
        $text = file_get_contents( filename: __DIR__ . '/FullRawOutput.txt');
        $output = API::parse_Mac_Address( input: $text );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'totals', object: $output );
        self::assertObjectHasProperty( propertyName: 'list', object: $output );
        self::assertIsObject( actual: $output->totals );
        self::assertIsArray( actual: $output->list );
    }

    public static function testParseTotals() : void
    {
        $text = file_get_contents( filename: __DIR__ . '/TotalsRawOutput.txt');
        $output = API::parse_Totals( input: $text );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'Total', object: $output );
    }

    public static function testParseList() : void
    {
        $text = file_get_contents( filename: __DIR__ . '/ListRawOutput.txt');
        $output = API::parse_Mac_List( input: $text );
        self::assertIsArray( actual: $output );
        self::assertIsObject( actual: $output[0] );
        self::assertObjectHasProperty( propertyName: 'mac', object: $output[0] );
    }
}