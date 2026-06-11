<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Traits\SSH;

use Ocolin\Hyconext\DTO\SSH\Port;
use RuntimeException;

trait PortTrait
{

/* GET INTERFACE PORTS
----------------------------------------------------------------------------- */

    /**
     * @return Port[] List of interface ports.
     * @throws RuntimeException Command failure.
     */
    public function getPorts() : array
    {
        $data = $this->runCommand( command: 'show interface' );

        $data = str_replace( search: "\r\n", replace: "\n", subject: $data );
        $rows = explode( separator: "\n", string: trim( $data ));
        // Trim unused rows
        array_shift( array: $rows ); // Remove command echo
        array_shift( array: $rows ); // Remove header row
        array_pop(   array: $rows ); // Remove command prompt

        $output = [];
        foreach( $rows as $row )
        {
            $columns = preg_split( pattern: "#\s+#", subject: trim( $row ) );
            if( $columns === false || count( $columns ) < 4 ) { continue; }

            $status = explode( separator: '/', string: $columns[1] );
            $description = $columns[3] === '-' ? null : $columns[3];

            $output[] = new Port(
                  interface: $columns[0],
                adminStatus: $status[0],
                 operStatus: $status[1] ?? 'unknown',
                       mode: $columns[2],
                description: $description
            );
        }

        return $output;
    }
}