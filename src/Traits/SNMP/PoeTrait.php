<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Traits\SNMP;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\Hyconext\DTO\SNMP\Poe;

trait PoeTrait
{
    private const string POE_TABLE = '1.3.6.1.4.1.60609.1.1622.1.1.1.';
    private const string POE_INDEX        = self::POE_TABLE . '1';
    private const string POE_ADMIN_ENABLE = self::POE_TABLE . '2';
    private const string POE_AVAILABILITY = self::POE_TABLE . '3';
    private const string POE_CONF_PAIRS   = self::POE_TABLE . '4';
    private const string POE_DETECTION    = self::POE_TABLE . '5';
    private const string POE_PRIORITY     = self::POE_TABLE . '6';
    private const string POE_MPS_ABSENT   = self::POE_TABLE . '7';
    private const string POE_TYPE         = self::POE_TABLE . '8';
    private const string POE_CLASS        = self::POE_TABLE . '9';
    private const string POE_INVALID_SIG  = self::POE_TABLE . '10';
    private const string POE_OVERLOAD     = self::POE_TABLE . '11';
    private const string POE_SHORT        = self::POE_TABLE . '12';
    private const string POE_MODE_STATE   = self::POE_TABLE . '14';
    private const string POE_MAX_POWER    = self::POE_TABLE . '18';
    private const string POE_PEAK_POWER   = self::POE_TABLE . '31';
    private const string POE_CUR_POWER    = self::POE_TABLE . '32';
    private const string POE_CURRENT      = self::POE_TABLE . '33';
    private const string POE_VOLTAGE      = self::POE_TABLE . '34';
    private const string POE_LABEL        = self::POE_TABLE . '37';
    private const string POE_OPER_PAIRS   = self::POE_TABLE . '44';
    private const string POE_PAIRS        = self::POE_TABLE . '45';


    private const array POE_COLUMN_MAP = [
        'adminEnable'      => self::POE_ADMIN_ENABLE,
        'availability'     => self::POE_AVAILABILITY,
        'configPairs'       => self::POE_CONF_PAIRS,
        'detection'        => self::POE_DETECTION,
        'priority'         => self::POE_PRIORITY,
        'mpsAbsent'        => self::POE_MPS_ABSENT,
        'type'             => self::POE_TYPE,
        'classification'   => self::POE_CLASS,
        'invalidSignature' => self::POE_INVALID_SIG,
        'overloadCounter'  => self::POE_OVERLOAD,
        'shortCounter'     => self::POE_SHORT,
        'modeState'        => self::POE_MODE_STATE,
        'maxPower'         => self::POE_MAX_POWER,
        'peakPower'        => self::POE_PEAK_POWER,
        'currentPower'     => self::POE_CUR_POWER,
        'currentMa'        => self::POE_CURRENT,
        'voltage'          => self::POE_VOLTAGE,
        'label'            => self::POE_LABEL,
        'operPairs'        => self::POE_OPER_PAIRS,
        'pairs'            => self::POE_PAIRS,
    ];



/* GET POE DATA
----------------------------------------------------------------------------- */

    /**
     * Get POE table.
     *
     * @param string[] $columns List of columns to return.
     * @return Poe[] List of POE ports.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error getting data from device.
     */
    public function getPoe(
        array $columns = [
            'adminEnable',
            'availability',
            'configPairs',
            'detection',
            'mpsAbsent',
            'type',
            'classification',
            'invalidSignature',
            'overloadCounter',
            'shortCounter',
            'modeState',
            'maxPower',
            'peakPower',
            'currentPower',
            'currentMa',
            'voltage',
            'label',
            'operPairs',
            'pairs',
        ]
    ) : array
    {
        $data = [];
        $ports = [];
        $indexes = $this->getColumn( oid: self::POE_INDEX );
        $count = count( $indexes );

        foreach( $columns as $column ) {
            if( isset( self::POE_COLUMN_MAP[$column] ) ) {
                $data[$column] = $this->getColumn( self::POE_COLUMN_MAP[$column], $count );
            }
        }

        foreach( $indexes as $index => $value ) {
            $ports[] = new Poe(
                          index: $index,
                     adminEnable: self::intVal( data: $data, key: 'adminEnable',      index: $index ),
                    availability: self::intVal( data: $data, key: 'availability',     index: $index ),
                     configPairs: self::intVal( data: $data, key: 'configPairs',      index: $index ),
                       detection: self::intVal( data: $data, key: 'detection',        index: $index ),
                        priority: self::intVal( data: $data, key: 'priority',         index: $index ),
                       mpsAbsent: self::intVal( data: $data, key: 'mpsAbsent',        index: $index ),
                            type: self::strVal( data: $data, key: 'type',             index: $index ),
                  classification: self::intVal( data: $data, key: 'classification',   index: $index ),
                invalidSignature: self::intVal( data: $data, key: 'invalidSignature', index: $index ),
                 overloadCounter: self::intVal( data: $data, key: 'overloadCounter',  index: $index ),
                    shortCounter: self::intVal( data: $data, key: 'shortCounter',     index: $index ),
                       modeState: self::intVal( data: $data, key: 'modeState',        index: $index ),
                        maxPower: self::intVal( data: $data, key: 'maxPower',         index: $index ),
                       peakPower: self::intVal( data: $data, key: 'peakPower',        index: $index ),
                    currentPower: self::intVal( data: $data, key: 'currentPower',     index: $index ),
                       currentMa: self::intVal( data: $data, key: 'currentMa',        index: $index ),
                         voltage: self::intVal( data: $data, key: 'voltage',          index: $index ),
                           label: self::strVal( data: $data, key: 'label',            index: $index ),
                       operPairs: self::intVal( data: $data, key: 'operPairs',        index: $index ),
                           pairs: self::intVal( data: $data, key: 'pairs',            index: $index ),
            );
        }

        return $ports;
    }
}