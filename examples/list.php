<?php

require_once ('common.php');

if (count($argv)!=3) {
    echo "Please pass 2 parameters: accountname and channelname\n";
    exit();
}

try {
    $accountName = $argv[1];
    $channelName = $argv[2];
    $files = $client->getFiles($accountName, $channelName);
    
    foreach ($files as $file) {
        echo "File: " . $file->getKey() . ': ' . $file->getName() . "\n";
        if ($file->getProperties()) {
            echo "   " . json_encode($file->getProperties()) . "\n";
        }
    }
} catch (Exception $e) {
    echo "Exception " . $e->getMessage() . "\n";
}
