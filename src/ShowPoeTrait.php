<?php
declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Exception;
use Ocolin\Hyconext\Objects\PoeApiObject;

trait ShowPoeTrait
{

/* SHOW POE INTERFACES
----------------------------------------------------------------------------- */

    /**
     * @return PoeApiObject[]|null List of POE interface objects.
     * @throws Exception
     */
    public function show_POE() : array|null
    {
        $output = $this->command( cmd: 'show pse interface' );
        if( !str_contains( $output, 'show pse interface' )) {
            throw new Exception( message: "Unable to connect to {$this->host}" );
        }

        return self::parse_POE( input: $output );
    }



/* PARSE POE INTERFACE OUTPUT
----------------------------------------------------------------------------- */

    /**
     * @param string|null $input Raw text from switch output.
     * @return PoeApiObject[]|null List of POE interface objects.
     */
    public static function parse_POE( ?string $input ) : array | null
    {
        if( $input === null ) { return null; }
        $output = [];

        list( $junk, $raw ) = explode( separator: 'show pse interface', string: $input );
        $list = explode( separator: "\n", string: trim( string: $raw ));
        array_shift( array: $list ); // REMOVE HEADINGS
        array_pop( array: $list ); // REMOVE PROMPT
        foreach( $list as $if )
        {
            $poe = new PoeApiObject();
            $columns = preg_split(
                pattern: "#\s+#",
                subject: trim( $if ),
                  limit: 10
            );
            $poe->interface = str_replace(
                 search: 'ge',
                replace: 'GigaEthernet',
                subject: array_shift( array: $columns )
            );
            $poe->state     = array_shift( array: $columns );
            $poe->mode      = array_shift( array: $columns );
            $poe->pair      = (int)array_shift( array: $columns );
            $poe->priority  = (int)array_shift( array: $columns );
            $poe->status    = array_shift( array: $columns );
            $poe->class     = array_shift( array: $columns );
            $poe->voltage   = (int)array_shift( array: $columns );
            $poe->power     = (int)array_shift( array: $columns );
            $poe->descr     = array_shift( array: $columns );

            $output[] = $poe;
        }

        return $output;
    }
}