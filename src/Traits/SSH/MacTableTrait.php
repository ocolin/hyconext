<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Traits\SSH;

use RuntimeException;
use Ocolin\Hyconext\DTO\SSH\MacEntry;
use Ocolin\Hyconext\DTO\SSH\MacStats;
use Ocolin\Hyconext\DTO\SSH\MacTable;

trait MacTableTrait
{

/* GET MAC TABLE
----------------------------------------------------------------------------- */

    /**
     * @return MacTable Formatted MAC table object.
     * @throws RuntimeException Error getting MAC table.
     */
    public function getMacTable() : MacTable
    {
        $output = $this->runCommand( command: 'show mac-address' );

        // Hyconext uses old \r returns.
        $output = str_replace( search: "\r\n", replace: "\n", subject: $output );
        $parts = explode( separator: "\n\n", string: $output );

        // $parts[0] = command echo (discarded)
        // $parts[1] = stats block
        // $parts[2] = MAC entries table
        if( count( $parts ) < 3 ) {
            throw new RuntimeException( message: 'Error parsing MAC Table.' );
        }

        return new MacTable(
            macStats: self::parseStats( $parts[1] ),
            macTable: self::parseEntries( $parts[2] ),
        );
    }



/* PARSE MAC STATS DATA
----------------------------------------------------------------------------- */

    /**
     * @param string $input Raw stats string.
     * @return MacStats MAC statistics object.
     */
    private static function parseStats( string $input ) : MacStats
    {
        $rows = explode( separator: "\n", string: trim( $input ));
        $array = [];
        foreach( $rows as $row ) {
            if( str_contains( haystack: $row, needle: ':' )) {
                [ $key, $val ] = explode(
                    separator: ':', string: $row, limit: 2
                );
                $array[ trim( $key )] = (int)trim( $val );
            }
        }

        return new MacStats(
                total: $array['Total']     ?? 0,
               static: $array['Static']    ?? 0,
              dynamic: $array['Dynamic']   ?? 0,
            blackhole: $array['Blackhole'] ?? 0,
               sticky: $array['Sticky']    ?? 0,
             security: $array['Security']  ?? 0,
             snooping: $array['Snooping']  ?? 0,
                valid: $array['Valid']     ?? 0,
        );
    }



/* PARSE MAC ENTRIES
----------------------------------------------------------------------------- */

    /**
     * @param string $input Raw string to parse.
     * @return MacEntry[] List of MAC entries.
     */
    private static function parseEntries( string $input ) : array
    {
        $output = [];
        $rows = explode( separator: "\n", string: trim( $input ));
        array_shift( array: $rows ); // Remove column headers
        array_pop( array: $rows ); // Remove command prompt

        foreach( $rows as $row ) {
            $columns = preg_split( pattern: "#\s+#", subject: trim( $row ));
            if( $columns === false ) { continue; }
            if( count( $columns ) < 5 ) { continue; }
            $vData = explode( separator: '/', string: trim( $columns[1] ));

            $output[] = new MacEntry(
                      mac: $columns[0],
                interface: $columns[2],
                 operType: $columns[3],
                     type: $columns[4],
                     vlan: is_numeric( $vData[0] ) ? (int)$vData[0] : null,
                      vsi: isset( $vData[1] ) && $vData[1] !== '--' ? $vData[1] : null,
                       bd: isset( $vData[2] ) && $vData[2] !== '--' ? $vData[2] : null,
            );
        }

        return $output;
    }
}