<?php

namespace FileBridge\Client;

use GuzzleHttp\Client as GuzzleClient;
use FileBridge\Client\Model\File;

class Client
{
    private $username;
    private $password;
    private $baseUrl;
    private $httpClient;

    public function __construct($baseUrl, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->baseUrl = $baseUrl;

        $this->httpClient = new GuzzleClient();
    }

    public function getFiles($accountName, $channelName)
    {
        $res = $this->httpClient->get(
            $this->baseUrl.'/api/v1/'.$accountName . '/' . $channelName . '/files',
            ['auth' => [$this->username, $this->password]]
        );

        if ($res->getStatusCode() != 200) {
            throw new \Exception(json_decode($res->getBody(), true)['error'], $res->getStatusCode());
        }
        
        $files = [];
        $data = json_decode($res->getBody(), true);
        foreach ($data as $fileData) {
            $file = new File();
            $filename = $fileData['name'];
            $filename = $this->sanitizeFilename($filename);
            $file->setName($filename);
            $file->setKey($fileData['key']);
            if (isset($fileData['properties'])) {
                $file->setProperties($fileData['properties']);
            }
            $files[] = $file;
        }

        return $files;
    }
    
    public function sanitizeFilename($filename)
    {
        $filename = str_replace('/', '_', $filename);
        $filename = str_replace('..', '_', $filename);
        $filename = trim($filename, '.');
        return $filename;
    }
    
    public function download($accountName, $channelName, $key, $filename)
    {
        $res = $this->httpClient->get(
            $this->baseUrl.'/api/v1/'.$accountName . '/' . $channelName . '/files/' . $key . '/download',
            ['auth' => [$this->username, $this->password]]
        );
        if ($res->getStatusCode() != 200) {
            throw new \Exception(json_decode($res->getBody(), true)['error'], $res->getStatusCode());
        }
        file_put_contents($filename, $res->getBody());
    }
    
    public function upload($accountName, $channelName, $filename, $metadata = null)
    {
        $url = $this->baseUrl.'/api/v1/'.$accountName . '/' . $channelName . '/upload';

        $options = [
            'Content-Type => multipart/form-data',
            'auth' => [
                $this->username,
                $this->password
            ]
        ];
        $fields = [];
        
        // construct multipart files array
        $files = [];
        $files[] = [
            'name'     => 'file',
            'contents' => file_get_contents($filename),
            'filename' => basename($filename)
        ];
        
        if ($metadata) {
            $files[] = [
                'name'     => 'metadata',
                'contents' => file_get_contents($metadata),
                'filename' => basename($metadata)
            ];
        }

        $options['multipart'] = $files;
        
        $res = $this->httpClient->request("POST", $url, $options);
        if ($res->getStatusCode() != 200) {
            throw new \Exception(json_decode($res->getBody(), true)['error'], $res->getStatusCode());
        }
        //print_r((string)$res->getBody());
        return json_decode($res->getBody(), true)['key'];

    }
}
