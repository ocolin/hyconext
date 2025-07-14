<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

class MacListObject
{
    /**
     * @var object Object of MAC type totals.
     */
    public object $totals;

    /**
     * @var array<MacObject> List of MAC address objects.
     */
    public array $list;
}