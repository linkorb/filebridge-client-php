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
        $filename = 'inbox/' . $file->getName();
        $client->download($accountName, $channelName, $file->getKey(), $filename);
        echo "File: " . $file->getKey() . ': ' . $file->getName() . "\n";
        if ($file->getProperties()) {
            echo "   With properties\n";
            file_put_contents(
                $filename . '.properties',
                json_encode(
                    $file->getProperties(),
                    JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
                )
            );
        }
    }
} catch (Exception $e) {
    echo "Exception " . $e->getMessage() . "\n";
}
