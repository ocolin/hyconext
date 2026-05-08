<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Ocolin\GlobalType\ENV;
use RuntimeException;

readonly class SshConfig
{
    public string $host;
    public string $username;
    public string $password;
    public int $port;
    public int $timeout;


/*
----------------------------------------------------------------------------- */

    /**
     * @param ?string $host Hostname/IP of device.
     * @param ?string $username Username to log in with.
     * @param ?string $password Password to log in with.
     * @param ?int $port SSH port number.
     * @param ?int $timeout How long before timing out connection.
     */
    public function __construct(
        ?string $host     = null,
        ?string $username = null,
        ?string $password = null,
           ?int $port     = null,
           ?int $timeout  = null
    ) {
        $this->host = $host
            ?? ENV::getStringNull( name: 'HYCONEXT_SSH_HOST' )
            ?? throw new RuntimeException( message:  'Missing SSH host' );

        $this->username = $username
            ?? ENV::getStringNull( name: 'HYCONEXT_SSH_USERNAME' )
            ?? 'admin';

        $this->password = $password
            ?? ENV::getStringNull( name: 'HYCONEXT_SSH_PASSWORD' )
            ?? throw new RuntimeException( message:  'Missing SSH password' );

        $this->port = $port
            ?? ENV::getIntNull( name: 'HYCONEXT_SSH_PORT' ) ?? 22;

        $this->timeout = $timeout
            ?? ENV::getIntNull( name: 'HYCONEXT_SSH_TIMEOUT' ) ?? 10;
    }
}