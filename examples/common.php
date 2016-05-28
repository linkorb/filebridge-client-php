<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use FileBridge\Client\Client;

$baseUrl = rtrim(getenv('FILEBRIDGE_BASEURL'), '/');
$username = getenv('FILEBRIDGE_USERNAME');
$password = getenv('FILEBRIDGE_PASSWORD');

if (!$username || !$password || !$baseUrl) {
    echo "Environment variables not yet properly configured\n";
    exit();
}

if (!file_exists('inbox/')) {
    throw new RuntimeException("Please create a `inbox/` directory");
}
if (!file_exists('outbox/')) {
    throw new RuntimeException("Please create a `outbox/` directory");
}

$client = new Client($baseUrl, $username, $password);
