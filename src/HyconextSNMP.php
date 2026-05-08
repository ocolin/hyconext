<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Ocolin\Hyconext\Traits\SNMP\PoeTrait;
use Ocolin\EasySNMP\EasySNMP;
use Ocolin\EasySNMP\Config;

class HyconextSNMP extends EasySNMP
{

    use PoeTrait;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * By default, we add a HYCONEXT prefix and look for env vars using that
     * prefix. For custom settings, use a new Config object.
     *
     * @param ?Config $config Configuration data from EasySNMP.
     */
    public function __construct( ?Config $config = null )
    {
        $config = $config ?? new Config( prefix: 'HYCONEXT' );
        parent::__construct( $config );
    }

}