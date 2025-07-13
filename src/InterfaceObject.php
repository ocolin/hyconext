<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext;

class InterfaceObject
{
    /**
     * @var string Interface Name.
     */
    public string $ifName;

    /**
     * @var string Interface Alias (label).
     */
    public string $ifAlias;

    /**
     * @var int Interface Incoming 32bit Multicast Packets.
     */
    public int $ifInMulticastPkts;

    /**
     * @var int Interface Incoming 32bit Broadcast Packets.
     */
    public int $ifInBroadcastPkts;

    /**
     * @var int Interface 32bit Outgoing Multicast Packets.
     */
    public int $ifOutMulticastPkts;

    /**
     * @var int Interface 32bit Outgoing Broadcast Packets.
     */
    public int $ifOutBroadcastPkts;

    /**
     * @var int Interface 64bit Incoming Traffic (in octets)
     */
    public int $ifHCInOctets;

    /**
     * @var int  Interface 64bit Incoming Ucast Packets.
     */
    public int $ifHCInUcastPkts;

    /**
     * @var int Interface 64bit Incoming Multicast Packets.
     */
    public int $ifHCInMulticastPkts;

    /**
     * @var int Interface 64bit Incoming Broadcast Packets.
     */
    public int $ifHCInBroadcastPkts;

    /**
     * @var int  Interface 64bit Outgoing Traffic in octets.
     */
    public int $ifHCOutOctets;

    /**
     * @var int Interface 64bit Outgoing Ucast packets.
     */
    public int $ifHCOutUcastPkts;

    /**
     * @var int Interface 64bit Outgoing Multicast Packets.
     */
    public int $ifHCOutMulticastPkts;

    /**
     * @var int  Interface 64bit Outgoing Broadcast packets.
     */
    public int $ifHCOutBroadcastPkts;

    /**
     * @var int  Interface Link Up/Down Traps enabled.
     */
    public int $ifLinkUpDownTrapEnable;

    /**
     * @var int  Interface 64bit Speed.
     */
    public int $ifHighSpeed;

    /**
     * @var int Interface Promiscuous Mode.
     */
    public int $ifPromiscuousMode;

    /**
     * @var int  Interface Connector Present.
     */
    public int $ifConnectorPresent;

    /**
     * @var int Interface Counter Discontinuity Time.
     */
    public int $ifCounterDiscontinuityTime;

}