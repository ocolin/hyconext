<?php
declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Exception;
use stdClass;

trait ShowMacAddressTrait
{

/* GET MAC ADDRESSES FROM SWITCH
----------------------------------------------------------------------------- */

    /**
     * @return MacObject Object containing MAC totals and list of addresses.
     * @throws Exception
     */
    public function show_Mac_Address() : MacObject
    {
        $cmd = 'show mac-address';
        $script = __DIR__ . '/script.expect';
        $args = "'{$this->host}' '{$this->user}' '{$this->pass}' '{$cmd}'";

        $output = shell_exec(
            command: "{$this->expect} {$script} {$args}",
        );

        if( $output === false OR $output === null ) {
            throw new Exception( message: 'Error talking to switch.' );
        }

        return self::parse_Mac_Address( input: $output );
    }



/* PARSE MAC ADDRESS OUTPUT
----------------------------------------------------------------------------- */

    /**
     * @param string $input Raw string output of MAC address command.
     * @return MacObject Object containing MAC totals, and list of MAC addresses.
     */
    public static function parse_Mac_Address( string $input ) : MacObject
    {
        $obj = new MacObject();
        list( $junk, $raw ) = explode( separator: 'show mac-address', string: $input );

        $raw = trim($raw);

        list( $totals_raw, $mac_raw)  = (array)preg_split( pattern: '#\n\s*\n#', subject: $raw );
        if( $mac_raw ) { $obj->list = self::parse_Mac_List( input: $mac_raw ); }
        if( $totals_raw ) { $obj->totals = self::parse_Totals( input: $totals_raw ); }

        return $obj;
    }



/* PARSE LIST OF MAC ADDRESSES
----------------------------------------------------------------------------- */

    /**
     * @param string $input String of mac list output.
     * @return array<object> Array of MAC address objects.
     */
    public static function parse_Mac_List( string $input ) : array
    {
        $output = [];
        $array = explode( separator: "\n", string: $input );
        unset( $array[0] );
        unset( $array[count( value: $array )] );

        foreach( $array as $line )
        {
            $obj = new stdClass();
            list( $mac, $vlan, $interface, $oper, $type) = (array)preg_split(
                pattern: '#\s+#', subject: trim( string: $line )
            );
            if( $mac ) { $obj->mac = strtoupper( string: $mac ); }
            $obj->interface = $interface;
            $obj->type      = $type;
            if( $oper ) { $obj->operation = $oper; }
            if( $vlan ) {
                list( $obj->vlan, $obj->vsi, $obj->bd ) = explode(
                    separator: '/', string: $vlan
                );
            }

            $output[] = $obj;
        }

        return $output;
    }



/* PARSE MAC ADDRESS TOTALS
----------------------------------------------------------------------------- */

    /**
     * @param string $input Raw string of totals output.
     * @return stdClass Object containing totals data.
     */
    public static function parse_Totals( string $input ) : object
    {
        $obj = new stdClass();
        $rows = explode( separator: "\n", string: $input );

        foreach( $rows as $row )
        {
            list( $title, $value ) = (array)preg_split(
                pattern: '#\s+:#', subject: trim( $row )
            );
            $obj->{$title} = (int)trim( (string)$value );
        }
        return $obj;
    }
}