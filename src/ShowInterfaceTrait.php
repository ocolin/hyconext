<?php
declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Exception;
use Ocolin\Hyconext\Objects\InterfaceApiObject;

trait ShowInterfaceTrait
{


/* SHOW INTERFACE COMMAND
----------------------------------------------------------------------------- */

    /**
     * @return InterfaceApiObject[]|null List of interface objects.
     * @throws Exception
     */
    public function show_Interface() : array|null
    {
        $output = $this->command( cmd: 'show interface' );
        if( !str_contains( $output, 'show interface' )) {
            throw new Exception( message: "Unable to connect to {$this->host}" );
        }

        return self::parse_Interface( input: $output );
    }



/* PARSE INTERFACE TEXT
----------------------------------------------------------------------------- */

    /**
     * @param string|null $input Raw text from switch output.
     * @return InterfaceApiObject[]|null List of interface objects.
     */
    public static function parse_Interface( ?string $input ) : array | null
    {
        if( $input === null ) { return null; }
        $output = [];
        list( $junk, $raw ) = explode( separator: 'show interface', string: $input );
        $list = explode( separator: "\n", string: trim( string: $raw ));

        array_shift( array: $list ); // REMOVE HEADINGS
        array_pop( array: $list ); // REMOVE PROMPT
        foreach( $list as $if )
        {
            $object = new InterfaceApiObject();
            $columns = preg_split(
                pattern: "#\s{2,}#", subject: trim( $if ), limit: 4
            );

            $object->interface = str_replace(
                search: 'ge', replace: 'GigaEthernet', subject: $columns[0] ?? ''
            );

            list( $object->admin, $object->oper ) = explode(
                separator: '/', string: $columns[1] ?? ''
            );

            $object->mode = $columns[2] ?? '';
            $object->descr = $columns[3] ?? '';

            $output[] = $object;
        }

        return $output;
    }
}