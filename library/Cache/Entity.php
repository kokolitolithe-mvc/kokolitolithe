<?php
class Cache_Entity implements Iterator{
	private $position = 0;
	protected $name;
	protected $datas;

	public function __construct($name = null) {
        $this->position = 0;
        if(!is_null($name))
        	$this->setName($name);
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

	public function setData($cacheData){
		$this->datas = $cacheData;
	}

	public function getData($id){
		return $this->datas[$id];
	}

	public function getAllData(){
		return $this->datas;
	}

	public function setFromArray($data){
		foreach($data as $key => $value){
			$cacheData = new Cache_Data();
			$cacheData->setName($key);
			$cacheData->setData($value);
			$this->datas[] = $cacheData;
		}
	}
}