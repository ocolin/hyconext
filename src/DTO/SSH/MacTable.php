<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\DTO\SSH;

readonly class MacTable
{
    /**
     * @param MacStats $macStats MAC table statistics.
     * @param MacEntry[] $macTable List of MAC address entries.
     */
    public function __construct(
        public MacStats $macStats,
           public array $macTable
    ) {}
}