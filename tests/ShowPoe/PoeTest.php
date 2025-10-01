<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests\ShowPoe;

use Ocolin\Hyconext\Tests\testTest;
use Ocolin\Hyconext\API;

class PoeTest extends testTest
{
    public static string $raw;

    public function testRawPoe() : void
    {
        $output = API::parse_POE( self::$raw );
    }

    public static function setUpBeforeClass() : void
    {
        self::$raw = file_get_contents( filename: __DIR__ . '/poeRaw.txt' );
    }
}