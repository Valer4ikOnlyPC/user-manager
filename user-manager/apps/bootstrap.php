<?php

declare(strict_types=1);

use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv('APP_ENV', 'APP_DEBUG'))->loadEnv(dirname(__DIR__) . '/.env');

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var(
    $_SERVER['APP_DEBUG'],
    FILTER_VALIDATE_BOOLEAN
) ? '1' : '0';

foreach ($_ENV as $envVariableName => $envVariableValue) {
    define("ENV_$envVariableName", $envVariableValue);
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    if (class_exists(Debug::class)) {
        Debug::enable();
    }
}
