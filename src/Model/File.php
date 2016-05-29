<?php

namespace FileBridge\Client\Model;

class File
{
    private $key;
    private $name;
    private $properties = null;
    
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getProperties()
    {
        return $this->properties;
    }
    
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }
    
    
    
}
