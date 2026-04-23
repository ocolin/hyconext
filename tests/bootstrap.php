<?php

declare( strict_types = 1 );

require_once __DIR__ . '/../vendor/autoload.php';

use Ocolin\EasyEnv\Env;

Env::load( files: __DIR__ . '/../.env', append: true );