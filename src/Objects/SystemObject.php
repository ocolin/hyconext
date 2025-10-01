<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Objects;

class SystemObject
{
    public function __construct(
        public ?string $descr = null,
        public ?string $id = null,
        public ?string $uptime = null,
        public ?string $contact = null,
        public ?string $name = null,
        public ?string $location = null,
    ){}
}