<?php

require_once ('common.php');

if (count($argv)!=3) {
    echo "Please pass 2 parameters: accountname and channelname\n";
    exit();
}

try {
    $accountName = $argv[1];
    $channelName = $argv[2];
    $filenames = glob('outbox/*');
    
    
    foreach ($filenames as $filename) {
        $key = null;
        echo "Uploading $filename\n";
        $key = $client->upload($accountName, $channelName, $filename);
        echo "   * Key: " . $key . "\n";
    }
} catch (Exception $e) {
    echo "Exception " . $e->getMessage() . "\n";
}
