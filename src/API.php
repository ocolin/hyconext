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
use ShowInterfaceTrait;
use ShowPoeTrait;


/*
----------------------------------------------------------------------------- */

    /**
     * @param string $cmd CLI command to run.
     * @return string Switch output text.
     * @throws Exception
     */
    public function command( string $cmd ) : string
    {
        $script = __DIR__ . '/script.expect';
        $args = "'{$this->host}' '{$this->user}' '{$this->pass}' '{$cmd}'";

        $output = shell_exec(
            command: "{$this->expect} {$script} {$args}",
        );

        if( $output === false OR $output === null ) {
            throw new Exception( message: 'Error talking to switch.' );
        }

        if( str_contains( $output, 'Connection refused' )) {
            throw new Exception( message: "Connection refused to {$this->host}" );
        }

        /*
        if( !str_contains( $output, 'show mac-address' )) {
            throw new Exception( message: "Unable to connect to {$this->host}" );
        }
        */

        return $output;
    }

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