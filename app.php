<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

$kernel = new Kernel();

if ('cli' === php_sapi_name()) {
    $kernel->runApplication();
}

$kernel->runRouter();
