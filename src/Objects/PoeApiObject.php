<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Objects;

class PoeApiObject
{
    /**
     * @var string Name of interface port.
     */
    public string $interface;

    /**
     * @var string State of enable or disable.
     */
    public string $state;

    /**
     * @var string Operation mode.
     */
    public string $mode;

    /**
     * @var int Number of pairs used.
     */
    public int $pair;

    /**
     * @var int Interface priority.
     */
    public int $priority;

    /**
     * @var string Power delivery status.
     */
    public string $status;

    /**
     * @var string POE class.
     */
    public string $class;

    /**
     * @var int Voltage level
     */
    public int $voltage;

    /**
     * @var int Power wattage.
     */
    public int $power;

    /**
     * @var string Description of port.
     */
    public string $descr;
}