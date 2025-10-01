<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Objects;

class InterfaceApiObject
{
    /**
     * @var string Name of interface port.
     */
    public string $interface;

    /**
     * @var string Administrative state.
     */
    public string $admin;

    /**
     * @var string Operational State.
     */
    public string $oper;

    /**
     * @var string Interface Mode.
     */
    public string $mode;

    /**
     * @var string Interface Description.
     */
    public string $descr;
}