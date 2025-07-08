# Hyconext

This is a little plugin to handle sending commands to a Hyconext switch. Regular ssh commands don't work, but expect does. Instead of requiring a PHP expect extension, it relies on the expect binary.

## Requirements

Must have expect binary on host system. 

## Setup

Constructor takes 4 arguments:

* $host - Name or IP of the Hyconext device.
* $pass - Password for Hyconext device.
* $user - Username of Hyconext device. Defaults to 'admin'.
* $path - Optional path to expect binary. Defaults to /usr/bin/expect. Should only be the path to expect and not the binary itself. If another binary is supplied only the path will be used.

### Example:

```php
$hyconextApi = Ocolin\Hyconext\API(
    host: '10.10.10.10',
    user: 'admin',
    pass: $_ENV['password']
);
```

## Calls

### Show Mac-Address

This command returns a list of the MAC table.

#### Example

```php
$output = $hyconextApi->show_Mac_Address();
```

This will return an object containing the property 'totals' with the totals of the type of addresses, and the property 'list' which will contain a list of objects representing the MAC addresses from the MAC table.