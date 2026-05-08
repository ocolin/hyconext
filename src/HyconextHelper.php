<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

use Ocolin\EasySNMP\SnmpHelper;

class HyconextHelper extends SNMPHelper
{

/* FORMAT ADMIN ENABLED
----------------------------------------------------------------------------- */

    /**
     * Format Admin Enable number to label.
     *
     * @param ?int $value Integer value.
     * @return ?string String label.
     */
    public static function formatPoeAdminEnable( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'enabled',
            2   => 'disabled',
            default => (string)$value
        };
    }



/* FORMAT POE AVAILABILITY
----------------------------------------------------------------------------- */

    /**
     * Format Availability number to label.
     *
     * @param ?int $value Integer value.
     * @return ?bool Boolean value.
     */
    public static function formatPoeAvailability( ?int $value ) : ?bool
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => true,
            default => false,
        };
    }



/* FORMAT CONFIGURED PAIRS
----------------------------------------------------------------------------- */

    /**
     * Format configured pairs number into a label.
     *
     * @param ?int $value Integer value.
     * @return ?string String label.
     */
    public static function formatConfigPairs( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => '2pair-low',
            2   => '4pair-low',
            3   => '2pair',
            4   => '4pair',
            5   => 'auto',
            default => (string)$value
        };
    }



/* FORMAT DETECTION
----------------------------------------------------------------------------- */

    /**
     * Format detection number into a label.
     *
     * @param ?int $value Integer value.
     * @return ?string String label.
     */
    public static function formatDetection( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'disabled',
            2   => 'searching',
            3   => 'deliveringPower',
            4   => 'fault',
            5   => 'test',
            6   => 'otherFault',
            default => (string)$value
        };
    }



/* FORMAT MODE STATE
----------------------------------------------------------------------------- */

    /**
     * Format Mode state number to a label.
     *
     * @param ?int $value Integer value.
     * @return ?string String label.
     */
    public static function formatModeState( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            0   => 'auto',
            2   => 'force',
            default => (string)$value
        };
    }



/* FORMAT OPERATING PAIRS
----------------------------------------------------------------------------- */

    /**
     * Format operational pairs number into a label.
     *
     * @param ?int $value Integer value.
     * @return ?string String label.
     */
    public static function formatOperPairs( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => '2pair-low',
            2   => '4pair-low',
            3   => '2pair',
            4   => '4pair',
            8   => 'auto(bt)',
            9   => 'auto',
            default => (string)$value
        };
    }
}