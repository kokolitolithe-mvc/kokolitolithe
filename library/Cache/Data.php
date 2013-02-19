<?php
class Cache_Data implements Iterator{
	private $position = 0;
	protected $name;
	protected $data;

	public function __construct() {
        $this->position = 0;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }

	public function setName($name){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data = $data;
	}
}