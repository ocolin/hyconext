<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\DTO\SSH;

readonly class MacEntry
{
    /**
     * @param string $mac MAC address
     * @param string $interface Interface MAC is found on.
     * @param string $operType Operation type.
     * @param string $type MAC address type.
     * @param ?int $vlan VLAN ID number.
     * @param ?string $vsi Virtual Switch Instance.
     * @param ?string $bd Bridge Domain
     */
    public function __construct(
        public  string $mac,
        public  string $interface,
        public  string $operType,
        public  string $type,
        public    ?int $vlan,
        public ?string $vsi,
        public ?string $bd,
    ) {}
}