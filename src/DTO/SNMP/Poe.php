<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\DTO\SNMP;

readonly class Poe
{
    /**
     * @param int $index Index of row.
     * @param ?int $adminEnable POE enabled/disabled.
     * @param ?int $availability POE availability. 1= true, 2 = false.
     * @param ?int $configPairs How pairs are configured.
     * @param ?int $detection Detect is power is being sent or otherwise.
     * @param ?int $priority
     * @param ?int $mpsAbsent
     * @param ?string $type
     * @param ?int $classification
     * @param ?int $invalidSignature
     * @param ?int $overloadCounter inferred from RFC 3621, empirically unconfirmed
     * @param ?int $shortCounter inferred from RFC 3621, empirically unconfirmed
     * @param ?int $modeState Port mode state. Empirically determined:
     * 0=auto, 2=force. No official documentation available.
     * @param ?int $maxPower Maximum allowed power in milliwatts.
     * @param ?int $peakPower Peak power detected in milliwatts.
     * @param ?int $currentPower Current power level in milliwatts.
     * @param ?int $currentMa Current in milliamps.
     * @param ?int $voltage Voltage in volts.
     * @param ?string $label
     * @param ?int $operPairs
     * @param ?int $pairs
     */
    public function __construct(
        public     int $index,
        public    ?int $adminEnable = null,
        public    ?int $availability = null,
        public    ?int $configPairs = null,
        public    ?int $detection = null,
        public    ?int $priority = null,
        public    ?int $mpsAbsent = null,
        public ?string $type = null,
        public    ?int $classification = null,
        public    ?int $invalidSignature = null,
        public    ?int $overloadCounter = null,
        public    ?int $shortCounter = null,
        public    ?int $modeState = null,
        public    ?int $maxPower = null,
        public    ?int $peakPower = null,
        public    ?int $currentPower = null,
        public    ?int $currentMa = null,
        public    ?int $voltage = null,
        public ?string $label = null,
        public    ?int $operPairs = null,
        public    ?int $pairs = null,
    ) {}
}