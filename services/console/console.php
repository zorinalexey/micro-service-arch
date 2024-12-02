<?php

use App\Core\Console\Console;

require_once __DIR__ . '/../crm/boot.php';

unset($argv[0]);

$console = new Console(...$argv);
$console->run();