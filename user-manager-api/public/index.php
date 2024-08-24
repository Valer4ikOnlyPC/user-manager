<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

use UserManager\UserManagerApi\Kernel;
use Symfony\Component\HttpFoundation\Request;

$rootDirInit = realpath(__DIR__ . '/../../');
$vendorDirInit = $rootDirInit . '/vendor';
require_once $vendorDirInit . '/autoload.php';
ini_set( 'default_charset', 'UTF-8' );

try {
    ob_start();

    $kernel = new Kernel(realpath(__DIR__) . '/../config');
    $request = Request::createFromGlobals();

    $response = $kernel->send($request);
} catch (Throwable $e) {
    $response = Kernel::createResponseFromException($e);
} finally {
    $response->send();
    if (false !== ob_get_length() && 0 < ob_get_length()) {
        ob_end_flush();
    }
}
