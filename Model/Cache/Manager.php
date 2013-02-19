<?php
class Model_Cache_Manager extends Model_Data_Cache{
    protected $dir;

    public function __construct(){
       $this->manager = $this->getManager();
    }

    public function getManager(){
        return Registry::getInstance()->get("Cache_Manager");
    }
}