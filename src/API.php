<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Exception;

class API
{
    /**
     * @var string Path to expect binary.
     */
    private string $expect = '/usr/bin/expect';


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string $host Host switch to query.
     * @param string $pass Password of switch.
     * @param string $user Username of switch.
     * @param string|null $path Path to expect binary.
     * @throws Exception Binary not found.
     */
    public function __construct(
        protected  string $host,
        protected  string $pass,
        protected  string $user = 'admin',
                  ?string $path = null
    )
    {
        if( $path !== null ) {
            $this->expect = self::format_Path( $path );
        }

        if( !file_exists( $this->expect )) {
            throw new Exception( message: 'Unable to find expect binary.' );
        }
    }

use ShowMacAddressTrait;

/* FORMAT BINARY PATH
----------------------------------------------------------------------------- */

    /**
     * @param string $path File path to expect binary.
     * @return string Formatted path.
     */
    public static function format_Path( string $path ) : string
    {
        $path = rtrim( string: $path, characters: 'expect' );
        $path = rtrim( string: $path, characters: '/' );

        return "{$path}/expect";
    }
}