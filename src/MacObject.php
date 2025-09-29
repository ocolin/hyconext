<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

class MacObject
{
    /**
     * @var string MAC address.
     */
    public string $mac;

    /**
     * @var string Switch interface MAC belongs to.
     */
    public string $interface;

    /**
     * @var string Type of host (dynamic, static, etc))
     */
    public string $type;

    /**
     * @var string Operation of host (forwarding, etc).
     */
    public string $operation;

    /**
     * @var int|string VLAN host belongs to.
     */
    public int|string $vlan;

    /**
     * @var string Virtual Switch Interface;
     */
    public string $vsi;

    /**
     * @var string Broadcast Domain.
     */
    public string $bd;

    /**
     * @var string Optional vendor field.
     */
    public string $vendor;
}