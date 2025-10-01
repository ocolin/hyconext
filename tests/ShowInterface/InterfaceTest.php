<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests\ShowInterface;

use Ocolin\Hyconext\Tests\testTest;
use Ocolin\Hyconext\API;

class InterfaceTest extends testTest
{
    public static string $raw;

    public function testApiInterfaceParse()
    {
        $output = API::parse_Interface( input: self::$raw );
        self::assertIsArray( $output );
        self::assertIsObject( $output[0] );
        self::assertObjectHasProperty( 'interface', $output[0] );
        self::assertObjectHasProperty( 'descr', $output[0] );
    }


    public static function setUpBeforeClass() : void
    {
        self::$raw = file_get_contents( filename: __DIR__ . '/FullRawOutput.txt' );
    }
}