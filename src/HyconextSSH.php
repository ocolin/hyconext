<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Exception;
use Ocolin\Hyconext\Traits\SSH\MacTableTrait;
use Ocolin\Hyconext\Traits\SSH\PortTrait;
use phpseclib3\Net\SSH2;
use RuntimeException;

class HyconextSSH
{
    /**
     * @var SSH2 SSH handler.
     */
    private SSH2 $ssh;

    use MacTableTrait;
    use PortTrait;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?SshConfig $config Configuration data object.
     * @throws Exception Failure to log in.
     */
    public function __construct( ?SshConfig $config = null )
    {
        $config = $config ?? new SshConfig();

        $this->ssh = new SSH2(
               host: $config->host,
               port: $config->port,
            timeout: $config->timeout,
        );


        if( !$this->ssh->login( $config->username, $config->password ) ) {
            throw new RuntimeException( message: 'SSH authentication failed' );
        }

        if( $this->ssh->read( expect: '#' ) === false ) {
            throw new RuntimeException( message: 'SSH connection lost after login' );
        }

        $this->ssh->write( cmd: "terminal length 0\n" );

        if( $this->ssh->read( expect: '#') === false ) {
            throw new RuntimeException(
                message: 'SSH connection lost after terminal length'
            );
        }
    }



/* RUN CLI COMMAND
----------------------------------------------------------------------------- */

    /**
     * @param string $command Command to run on CLI.
     * @return string Command response output.
     * @throws RuntimeException Error executing command.
     */
    protected function runCommand( string $command ) : string
    {
        $this->ssh->write(  cmd: $command . "\n" );

        $output =  $this->ssh->read( expect: '#' );

        if( !is_string( $output)) {
            throw new RuntimeException(
                message: 'Error executing SSH command: ' . $command
            );
        }

        return $output;
    }



/* DESTRUCTOR
----------------------------------------------------------------------------- */

    public function __destruct()
    {
        $this->ssh->disconnect();
    }
}