<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\DTO\SSH;

readonly class MacStats
{
    /**
     * @param int $total Total number of MAC addresses.
     * @param int $static Statically assigned addresses.
     * @param int $dynamic Dynamically assigned addresses.
     * @param int $blackhole MAC addresses that are deliberately
     * dropped, used to block specific devices
     * @param int $sticky MAC addresses that were dynamically
     * learned but are saved to config, persisting across reboots
     * @param int $security MAC addresses learned through port security
     * features, limiting which devices can connect
     * @param int $snooping MAC addresses learned through DHCP snooping,
     * a security feature that tracks DHCP assignments
     * @param int $valid Valid addresses.
     */
    public function __construct(
        public int $total,
        public int $static,
        public int $dynamic,
        public int $blackhole,
        public int $sticky,
        public int $security,
        public int $snooping,
        public int $valid
    ) {}
}