<?php

use App\Core\Helpers\Arr;

const APP_PATH = __DIR__;
const CONFIG_PATH = APP_PATH . '/config';
const STORAGE_PATH = APP_PATH . '/storage';
const RESOURCES_PATH = APP_PATH . '/resources';
const COMMAND_PATH = APP_PATH . '/Console/Commands';

require_once 'vendor/autoload.php';

ini_set('memory_limit', -1);

date_default_timezone_set(Arr::get(config('app'), 'timezone', 'UTC'));