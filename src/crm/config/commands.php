<?php

use App\Commands\DB\DumpCreateCommand;
use App\Core\Console\Commands\Schedule;

return [
    'schedule' => [
        'class' => Schedule::class,
    ],
    'db:dump' => [
        'class' => DumpCreateCommand::class,
    ]
];