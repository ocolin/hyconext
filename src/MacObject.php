<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

class MacObject
{
    /**
     * @var object Object of MAC type totals.
     */
    public object $totals;

    /**
     * @var array<object> List of MAC address objects.
     */
    public array $list;
}