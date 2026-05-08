<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests\Unit;

use Ocolin\Hyconext\SshConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass( SshConfig::class )]
#[Group( 'unit' )]
class SshConfigTest extends TestCase
{

    /* CONSTRUCTOR ARGUMENT TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function constructorSetsHostFromArgument() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );
        $this->assertSame( '192.168.1.1', $config->host );
    }

    #[Test]
    public function constructorSetsPasswordFromArgument() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );
        $this->assertSame( 'secret', $config->password );
    }

    #[Test]
    public function constructorSetsUsernameFromArgument() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret', username: 'myuser' );
        $this->assertSame( 'myuser', $config->username );
    }

    #[Test]
    public function constructorSetsPortFromArgument() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret', port: 2222 );
        $this->assertSame( 2222, $config->port );
    }

    #[Test]
    public function constructorSetsTimeoutFromArgument() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret', timeout: 30 );
        $this->assertSame( 30, $config->timeout );
    }



    /* DEFAULT VALUE TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function constructorDefaultsUsernameToAdmin() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );
        $this->assertSame( 'admin', $config->username );
    }

    #[Test]
    public function constructorDefaultsPortTo22() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );
        $this->assertSame( 22, $config->port );
    }

    #[Test]
    public function constructorDefaultsTimeoutTo10() : void
    {
        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );
        $this->assertSame( 10, $config->timeout );
    }



    /* EXCEPTION TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function constructorThrowsWhenHostMissing() : void
    {
        $this->expectException( RuntimeException::class );
        $this->expectExceptionMessage( 'Missing SSH host' );
        new SshConfig( password: 'secret' );
    }

    #[Test]
    public function constructorThrowsWhenPasswordMissing() : void
    {
        $this->expectException( RuntimeException::class );
        $this->expectExceptionMessage( 'Missing SSH password' );
        new SshConfig( host: '192.168.1.1' );
    }



    /* ENV VAR TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function constructorReadsHostFromEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST'] = '10.0.0.1';
        $_ENV['HYCONEXT_SSH_PASSWORD'] = 'envpassword';

        $config = new SshConfig();

        unset( $_ENV['HYCONEXT_SSH_HOST'], $_ENV['HYCONEXT_SSH_PASSWORD'] );

        $this->assertSame( '10.0.0.1', $config->host );
    }

    #[Test]
    public function constructorReadsPasswordFromEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST'] = '10.0.0.1';
        $_ENV['HYCONEXT_SSH_PASSWORD'] = 'envpassword';

        $config = new SshConfig();

        unset( $_ENV['HYCONEXT_SSH_HOST'], $_ENV['HYCONEXT_SSH_PASSWORD'] );

        $this->assertSame( 'envpassword', $config->password );
    }

    #[Test]
    public function constructorArgumentOverridesEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST'] = '10.0.0.1';

        $config = new SshConfig( host: '192.168.1.1', password: 'secret' );

        unset( $_ENV['HYCONEXT_SSH_HOST'] );

        $this->assertSame( '192.168.1.1', $config->host );
    }

    #[Test]
    public function constructorReadsPortFromEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST']     = '10.0.0.1';
        $_ENV['HYCONEXT_SSH_PASSWORD'] = 'secret';
        $_ENV['HYCONEXT_SSH_PORT']     = '2222';

        $config = new SshConfig();

        unset(
            $_ENV['HYCONEXT_SSH_HOST'],
            $_ENV['HYCONEXT_SSH_PASSWORD'],
            $_ENV['HYCONEXT_SSH_PORT']
        );

        $this->assertSame( 2222, $config->port );
    }

    #[Test]
    public function constructorReadsTimeoutFromEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST']     = '10.0.0.1';
        $_ENV['HYCONEXT_SSH_PASSWORD'] = 'secret';
        $_ENV['HYCONEXT_SSH_TIMEOUT']  = '30';

        $config = new SshConfig();

        unset(
            $_ENV['HYCONEXT_SSH_HOST'],
            $_ENV['HYCONEXT_SSH_PASSWORD'],
            $_ENV['HYCONEXT_SSH_TIMEOUT']
        );

        $this->assertSame( 30, $config->timeout );
    }

    #[Test]
    public function constructorReadsUsernameFromEnv() : void
    {
        $_ENV['HYCONEXT_SSH_HOST']     = '10.0.0.1';
        $_ENV['HYCONEXT_SSH_PASSWORD'] = 'secret';
        $_ENV['HYCONEXT_SSH_USERNAME'] = 'myuser';

        $config = new SshConfig();

        unset(
            $_ENV['HYCONEXT_SSH_HOST'],
            $_ENV['HYCONEXT_SSH_PASSWORD'],
            $_ENV['HYCONEXT_SSH_USERNAME']
        );

        $this->assertSame( 'myuser', $config->username );
    }

}