<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Ocolin\EasySNMP\Errors\EasySnmpInvalidCmdError;
use Ocolin\EasySNMP\Errors\EasySnmpInvalidIpError;
use Ocolin\EasySNMP\Errors\EasySnmpInvalidOidError;
use Ocolin\EasySNMP\Errors\EasySnmpMissingCommunityError;
use Ocolin\EasySNMP\SNMP AS EasySNMP;
use stdClass;

class SNMP
{
    public EasySNMP $snmp;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string $host IP address of Hyconext device.
     * @param string|null $community SNMP community string.
     * @param int $version SNMP version (1 or 2 only).
     * @param string $prefix Environment prefix (ex: DEVICE for DEVICE_SNMP_VERSION)
     * @throws EasySnmpInvalidIpError
     * @throws EasySnmpMissingCommunityError
     */
    public function __construct(
         public string $host,
               ?string $community = null,
                   int $version = 2,
                string $prefix = ''
    )
    {
        $this->snmp = new EasySNMP(
                   ip: $this->host,
            community: $community,
              version: $version,
               prefix: $prefix
        );
    }



/* GET INTERFACE DATA
----------------------------------------------------------------------------- */

    /**
     * @return InterfaceObject[]
     * @throws EasySnmpInvalidCmdError
     * @throws EasySnmpInvalidOidError
     */
    public function get_Interfaces() : array
    {
        return self::parse_Interfaces(
            raw: $this->snmp->walk( oid: '.1.3.6.1.2.1.31' )
        );
    }



/* PARSE THE RAW SNMP OUTPUT DATA INTO INTERFACE OBJECTS
----------------------------------------------------------------------------- */

    /**
     * @param array<object> $raw Raw table output from EasySNMP
     * @return InterfaceObject[] Array of formatted interfaces.
     */
    public static function parse_Interfaces( array $raw ) : array
    {
        $output = [];
        foreach( $raw as $object )
        {
            if(
                isset( $object->oid ) AND
                isset( $object->value ) AND
                str_contains( haystack: $object->oid, needle: '.' ) AND
                gettype( value: $object->oid ) == 'string'
            ) {
                list( $name, $index ) = explode( separator: '.', string: $object->oid );
                if( !array_key_exists( key: $index, array: $output )) {
                    $output[$index] = new InterfaceObject();
                }

                if( is_numeric( $object->value )){ $object->value = (int)$object->value; }

                $output[$index]->{$name} = $object->value;
            }
        }

        return $output;
    }

}