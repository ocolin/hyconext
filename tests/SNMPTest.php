<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests;

use Ocolin\Hyconext\SNMP;

class SNMPTest extends testTest
{

    public function testInterfaces() : void
    {
        $o  = new SNMP( host: $_ENV['TEST_IP_ADDRESS'], prefix: 'DEVICE' );
        $output = $o->get_Interfaces();
        $if = reset( $output );

        self::assertIsArray( $output );
        self::assertIsObject( $if );
        self::assertObjectHasProperty( 'ifAlias', $if );
    }


    public function testSystem() : void
    {
        //get_System
        $o  = new SNMP( host: $_ENV['TEST_IP_ADDRESS'], prefix: 'DEVICE' );
        $output = $o->get_system();
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'descr', $output );
        self::assertObjectHasProperty( 'name', $output );
    }
}