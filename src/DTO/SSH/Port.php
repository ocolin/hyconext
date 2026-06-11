<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\DTO\SSH;

readonly class Port
{
    public function __construct(
        public string  $interface,
        public string $adminStatus,  // 'up' or 'down' (first part of State)
        public string $operStatus,  // 'up' or 'down' (second part of State)
        public string $mode,
        public ?string $description = null,
    ) {}
}