<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Objects;

class InterfaceSnmpObject
{
    /**
     * @var string Interface Name.
     */
    public string $Name;

    /**
     * @var string Interface Alias (label).
     */
    public string $Alias;

    /**
     * @var int Interface Incoming 32bit Multicast Packets.
     */
    public int $InMulticastPkts;

    /**
     * @var int Interface Incoming 32bit Broadcast Packets.
     */
    public int $InBroadcastPkts;

    /**
     * @var int Interface 32bit Outgoing Multicast Packets.
     */
    public int $OutMulticastPkts;

    /**
     * @var int Interface 32bit Outgoing Broadcast Packets.
     */
    public int $OutBroadcastPkts;

    /**
     * @var int Interface 64bit Incoming Traffic (in octets)
     */
    public int $HCInOctets;

    /**
     * @var int  Interface 64bit Incoming Ucast Packets.
     */
    public int $HCInUcastPkts;

    /**
     * @var int Interface 64bit Incoming Multicast Packets.
     */
    public int $HCInMulticastPkts;

    /**
     * @var int Interface 64bit Incoming Broadcast Packets.
     */
    public int $HCInBroadcastPkts;

    /**
     * @var int  Interface 64bit Outgoing Traffic in octets.
     */
    public int $HCOutOctets;

    /**
     * @var int Interface 64bit Outgoing Ucast packets.
     */
    public int $HCOutUcastPkts;

    /**
     * @var int Interface 64bit Outgoing Multicast Packets.
     */
    public int $HCOutMulticastPkts;

    /**
     * @var int  Interface 64bit Outgoing Broadcast packets.
     */
    public int $HCOutBroadcastPkts;

    /**
     * @var int  Interface Link Up/Down Traps enabled.
     */
    public int $LinkUpDownTrapEnable;

    /**
     * @var int  Interface 64bit Speed.
     */
    public int $HighSpeed;

    /**
     * @var int Interface Promiscuous Mode.
     */
    public int $PromiscuousMode;

    /**
     * @var int  Interface Connector Present.
     */
    public int $ConnectorPresent;

    /**
     * @var int Interface Counter Discontinuity Time.
     */
    public int $CounterDiscontinuityTime;

}