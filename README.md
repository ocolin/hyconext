[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/ocolin/hyconext)](https://packagist.org/packages/ocolin/hyconext)
[![Downloads](https://img.shields.io/packagist/dt/ocolin/hyconext)](https://packagist.org/packages/ocolin/hyconext)
# ocolin/hyconext

A PHP client for monitoring and managing **Hyconext** network switches via SNMP and SSH. Provides structured access to interface data, system information, POE port statistics, and MAC address tables.

Built on top of [ocolin/EasySNMP](https://github.com/ocolin/EasySNMP) for SNMP and [phpseclib](https://phpseclib.com) for SSH.

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
    - [SNMP Configuration](#snmp-configuration)
    - [SSH Configuration](#ssh-configuration)
- [Usage](#usage)
    - [SNMP Client](#snmp-client)
    - [SSH Client](#ssh-client)
- [Available Methods](#available-methods)
    - [HyconextSNMP](#hyconextsnmp)
    - [HyconextSSH](#hyconextssh)
- [Data Objects](#data-objects)
    - [Port](#port)
    - [Poe](#poe)
    - [System](#system)
    - [MacTable](#mactable)
- [Notes](#notes)
- [Contributing & Roadmap](#contributing--roadmap)

---

## Requirements

- PHP 8.4 or higher
- `ext-openssl`
- `ext-gmp`
- `expect` binary (for SSH MAC table retrieval — `sudo apt install expect` on Debian/Ubuntu)

---

## Installation

```bash
composer require ocolin/hyconext
```

---

## Configuration

Configuration can be provided in three ways:

**1. Environment variables with the default prefix** — the simplest approach. Set `HYCONEXT_*` variables in your environment and instantiate with no arguments:

```php
$snmp = new HyconextSNMP();
$ssh  = new HyconextSSH();
```

**2. Environment variables with a custom prefix** — useful when managing multiple devices with different credentials, or if you prefer a different naming convention:

```php
$config = new Config( prefix: 'SWITCH_FLOOR2' );
$snmp   = new HyconextSNMP( $config );
```

With `SWITCH_FLOOR2_SNMP_HOST`, `SWITCH_FLOOR2_SNMP_COMMUNITY` etc. as your ENV vars.

**3. Direct constructor arguments** — pass values explicitly, bypassing ENV vars entirely:

```php
$config = new Config( host: '192.168.1.1', community: 'public' );
$snmp   = new HyconextSNMP( $config );
```

Constructor arguments always take priority over environment variables. The tables below list all available parameters, their ENV suffix, and defaults. The full ENV var name is `{PREFIX}{SUFFIX}` where the prefix defaults to `HYCONEXT` — for example `HYCONEXT_SNMP_HOST`.

### SNMP Configuration

| Argument | ENV Suffix | Type | Default | Description |
|----------|------------|------|---------|-------------|
| `host` | `_SNMP_HOST` | string | — | Hostname or IP of device |
| `community` | `_SNMP_COMMUNITY` | string | `public` | SNMP community string (v2c) |
| `port` | `_SNMP_PORT` | int | `161` | SNMP UDP port |
| `version` | `_SNMP_VERSION` | int | `2` | SNMP version (1, 2, or 3) |
| `timeout_connect` | `_SNMP_TIMEOUT_CONNECT` | int | `3` | Connection timeout in seconds |
| `timeout_read` | `_SNMP_TIMEOUT_READ` | int | `10` | Read timeout in seconds |
| `user` | `_SNMP_USER` | string | — | SNMPv3 username |
| `use_auth` | `_SNMP_USE_AUTH` | bool | `true` | SNMPv3 enable authentication |
| `auth_pwd` | `_SNMP_AUTH_PWD` | string | — | SNMPv3 authentication password |
| `auth_mech` | `_SNMP_AUTH_MECH` | string | `SHA` | SNMPv3 authentication mechanism |
| `use_priv` | `_SNMP_USE_PRIV` | bool | `true` | SNMPv3 enable privacy/encryption |
| `priv_mech` | `_SNMP_PRIV_MECH` | string | `AES` | SNMPv3 privacy mechanism |
| `priv_pwd` | `_SNMP_PRIV_PWD` | string | — | SNMPv3 privacy password |
| `engine_id` | `_SNMP_ENGINE_ID` | string | — | SNMPv3 engine ID (usually auto-discovered) |
| `context_name` | `_SNMP_CONTEXT_NAME` | string | — | SNMPv3 context name |

### SSH Configuration

| Argument | ENV Suffix | Type | Default | Description |
|----------|------------|------|---------|-------------|
| `host` | `_SSH_HOST` | string | — | Hostname or IP of device |
| `username` | `_SSH_USERNAME` | string | `admin` | SSH username |
| `password` | `_SSH_PASSWORD` | string | — | SSH password |
| `port` | `_SSH_PORT` | int | `22` | SSH port |
| `timeout` | `_SSH_TIMEOUT` | int | `10` | SSH timeout in seconds |

---

## Usage

### SNMP Client

```php
use Ocolin\Hyconext\HyconextSNMP;
use Ocolin\EasySNMP\Config;

// Using environment variables
$snmp = new HyconextSNMP();

// Using a config object directly
$config = new Config(
    host: '192.168.1.1',
    community: 'public',
    prefix: 'HYCONEXT'
);
$snmp = new HyconextSNMP( $config );

// Get system information
$system = $snmp->getSystem();
echo $system->sysName;      // 877Cedar.POESW
echo $system->sysUpTime;    // Raw TimeTicks value
echo $system->sysLocation;  // 877Cedar

// Get interfaces (default columns)
$interfaces = $snmp->getInterfaces();
foreach( $interfaces as $port ) {
    echo $port->name . ' - ' . $port->alias . ' - ' . $port->operStatus;
}

// Get interfaces (specific columns only)
$interfaces = $snmp->getInterfaces(['name', 'alias', 'operStatus', 'highSpeed']);

// Get POE data (all columns)
$poe = $snmp->getPoe();
foreach( $poe as $port ) {
    echo $port->label . ': ' . $port->currentPower . 'mW';
}

// Get POE data (specific columns only)
$poe = $snmp->getPoe(['adminEnable', 'detection', 'currentPower', 'label']);
```

### SSH Client

The SSH client is used to retrieve the MAC address table, which is not available via SNMP on Hyconext switches.

```php
use Ocolin\Hyconext\HyconextSSH;
use Ocolin\Hyconext\SshConfig;

// Using environment variables
$ssh = new HyconextSSH();

// Using a config object directly
$config = new SshConfig(
    host: '192.168.1.1',
    password: 'yourpassword'
);
$ssh = new HyconextSSH( $config );

// Get MAC address table
$macTable = $ssh->getMacTable();

echo $macTable->macStats->total;    // Total MAC addresses
echo $macTable->macStats->dynamic;  // Dynamic entries
echo $macTable->macStats->static;   // Static entries

foreach( $macTable->macTable as $entry ) {
    echo $entry->mac . ' on ' . $entry->interface . ' (VLAN ' . $entry->vlan . ')';
}
```

---

## Available Methods

### HyconextSNMP

| Method | Returns | Description |
|--------|---------|-------------|
| `getSystem()` | `System` | Device system information |
| `getInterfaces( array $columns )` | `Port[]` | Interface/port data |
| `getPoe( array $columns )` | `Poe[]` | POE port data |

#### getInterfaces() Columns

| Column | Type | Description |
|--------|------|-------------|
| `name` | string | Interface name (e.g. `2.5GigaEthernet1/0/1`) |
| `alias` | string | Administrator-assigned description |
| `description` | string | Interface description |
| `speed` | int | Speed in bps |
| `highSpeed` | int | Speed in Mbps |
| `adminStatus` | int | Administrative status (1=up, 2=down, 3=testing) |
| `operStatus` | int | Operational status (1=up, 2=down, 3=testing, 4=unknown, 5=dormant, 6=notPresent, 7=lowerLayerDown) |
| `mtu` | int | Maximum transmission unit in bytes |
| `macAddress` | string | Interface MAC address |
| `type` | int | Interface type (see IANA ifType registry) |
| `lastChange` | int | Last status change in TimeTicks |
| `inOctets` | int | 32-bit input byte counter (wraps at ~4GB) |
| `outOctets` | int | 32-bit output byte counter (wraps at ~4GB) |
| `inHcOctets` | int | 64-bit input byte counter |
| `outHcOctets` | int | 64-bit output byte counter |
| `inErrors` | int | Input error counter |
| `outErrors` | int | Output error counter |

Default columns: `name`, `alias`, `description`, `speed`, `operStatus`, `adminStatus`

#### getPoe() Columns

| Column | Type | Description |
|--------|------|-------------|
| `adminEnable` | int | POE enabled (1=enabled, 2=disabled) |
| `availability` | int | Power pairs control ability (TruthValue) |
| `configPair` | int | Configured pair mode (1=2pair-low, 2=4pair-low, 3=2pair, 4=4pair, 5=auto) |
| `detection` | int | Detection status (1=disabled, 2=searching, 3=deliveringPower, 4=fault, 5=test, 6=otherFault) |
| `priority` | int | Port power priority (1-16) |
| `mpsAbsent` | int | Power dropout counter |
| `type` | string | Port type string |
| `classification` | int | POE device class (0-4) |
| `invalidSignature` | int | Invalid signature counter |
| `overloadCounter` | int | Overload event counter |
| `shortCounter` | int | Short circuit counter |
| `modeState` | int | Port mode state (0=auto, 2=force) |
| `maxPower` | int | Configured max power in milliwatts |
| `peakPower` | int | Peak power consumption in milliwatts |
| `currentPower` | int | Current power consumption in milliwatts |
| `currentMa` | int | Current draw in milliamps |
| `voltage` | int | Port voltage in volts |
| `label` | string | Administrator-assigned POE label |
| `operPairs` | int | Operating pair mode (1=2pair-low, 2=4pair-low, 3=2pair, 4=4pair, 8=auto-bt, 9=auto) |
| `pairs` | int | Active pair count |

Default: All columns

### HyconextSSH

| Method | Returns | Description |
|--------|---------|-------------|
| `getMacTable()` | `MacTable` | MAC address table |

---

## Data Objects

### Port

Returned by `getInterfaces()`. All properties except `index` are nullable depending on requested columns.

```php
$port->index;        // int     - Interface index
$port->name;         // ?string - Interface name (e.g. 2.5GigaEthernet1/0/1)
$port->alias;        // ?string - Admin description (e.g. 877Cedar-903Pacific)
$port->operStatus;   // ?int    - Raw operational status (1=up, 2=down)
$port->adminStatus;  // ?int    - Raw admin status (1=up, 2=down)
$port->speed;        // ?int    - Speed in bps
$port->highSpeed;    // ?int    - Speed in Mbps
$port->inHcOctets;   // ?int    - 64-bit input bytes
$port->outHcOctets;  // ?int    - 64-bit output bytes
```

### Poe

Returned by `getPoe()`. All properties except `index` are nullable depending on requested columns.

```php
$poe->index;          // int     - Interface index (matches Port index)
$poe->adminEnable;    // ?int    - 1=enabled, 2=disabled
$poe->detection;      // ?int    - 1=disabled, 2=searching, 3=deliveringPower, 4=fault
$poe->currentPower;   // ?int    - Current consumption in milliwatts
$poe->peakPower;      // ?int    - Peak consumption in milliwatts
$poe->maxPower;       // ?int    - Configured power limit in milliwatts
$poe->voltage;        // ?int    - Port voltage in volts
$poe->currentMa;      // ?int    - Current draw in milliamps
$poe->label;          // ?string - POE port label
```

### System

Returned by `getSystem()`.

```php
$system->sysName;        // ?string - Device hostname
$system->sysDescr;       // ?string - Hardware/firmware description
$system->sysLocation;    // ?string - Physical location
$system->sysContact;     // ?string - Contact information
$system->sysUpTime;      // ?int    - Uptime in TimeTicks (hundredths of a second)
```

### MacTable

Returned by `getMacTable()`.

```php
$macTable->macStats->total;    // int - Total MAC entries
$macTable->macStats->dynamic;  // int - Dynamic entries
$macTable->macStats->static;   // int - Static entries
$macTable->macStats->valid;    // int - Valid entries

foreach( $macTable->macTable as $entry ) {
    $entry->mac;        // string  - MAC address (xx:xx:xx:xx:xx:xx)
    $entry->interface;  // string  - Interface name
    $entry->vlan;       // ?int    - VLAN ID
    $entry->operType;   // string  - Operation type (forward, discard, etc.)
    $entry->type;       // string  - Entry type (dynamic, static, etc.)
    $entry->vsi;        // ?string - Virtual Switch Instance (null if not used)
    $entry->bd;         // ?string - Bridge Domain (null if not used)
}
```

---

## Notes

- **MAC table via SNMP**: Hyconext switches do not expose the MAC address table via SNMP despite having a documented OID for it. The SSH client is provided as a workaround.
- **POE data**: The POE trait uses Hyconext's proprietary SNMP MIB (`1.3.6.1.4.1.60609.1.1622`). Some columns were determined empirically due to incomplete MIB documentation. These are noted in the source code.
- **SSH compatibility**: The SSH client has been tested against `dropbear_USP_SSH` as used by Hyconext switches. Standard phpseclib password authentication is used.
- **Column 43 timeout**: SNMP column `.43` of the proprietary POE table causes the device to timeout and is intentionally skipped.
- **Interface name format**: SNMP returns full interface names (`2.5GigaEthernet1/0/1`) while SSH returns abbreviated names (`2.5ge1/0/1`). Keep this in mind when correlating data between the two clients.
- **Raw values**: All SNMP integer values are returned as-is without string conversion. This keeps the library lightweight and lets consumers format values as needed for their use case.t**: SNMP returns full interface names (`2.5GigaEthernet1/0/1`) while SSH returns abbreviated names (`2.5ge1/0/1`). Keep this in mind when correlating data between the two clients.

---

## Contributing & Roadmap

This package is under active development. The current release covers the most commonly needed functionality — interfaces, system information, POE, and MAC address tables — but Hyconext switches support much more than what is documented here.

Planned additions include more SNMP traits for things like routing tables, ARP tables, and VLAN information. If there is a specific feature you need that isn't yet implemented, feel free to open an issue on [GitHub](https://github.com/ocolin/hyconext/issues) and it will be considered for a future release. Pull requests are also welcome.