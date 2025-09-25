<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests;

use Ocolin\Hyconext\API;

class MacListTest extends testTest
{
    public function testList() : void
    {
        $o = new API(
            host: $_ENV['TEST_IP_ADDRESS'],
            pass: $_ENV['TEST_PASSWORD']
        );

        $output = $o->show_Mac_Address();
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'totals', $output );
        self::assertObjectHasProperty( 'list', $output );
        self::assertIsObject( $output->totals );
        self::assertObjectHasProperty( 'Total', $output->totals );
        self::assertIsArray( $output->list );
        self::assertIsObject( $output->list[0] );

    }
}