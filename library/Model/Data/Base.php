<?php
abstract class Model_Data_Base extends Model_Abstract{
	protected $table = "";
	protected $timestampable = false; // si ce flag est à true, il faut des champs created_at et updated_at
	protected $primary = array('id');
	protected $_wrapper;

	public function __construct()
	{
        $this->where = "database";
		$this->_wrapper = DataBase::instance();
	}

	public function error()
	{
		return $this->_wrapper->getErrorMessage();
	}

	public function find($id)
	{
		$data = $this->_wrapper->queryFirst("SELECT * FROM ".$this->table." WHERE id = :id",array('id' => $id));
		if(!empty($data)){
			$this->setFromArray($data);
			return $data;
		}
		else{
			return false;
		}
	}

	public function findBy(array $params){
		$data = $this->_wrapper->select($this->table,$params);
		if(!empty($data)){
			return $data;
		}
		else{
			return false;
		}
	}

	public function fetchAll(){
		return $this->_wrapper->query("SELECT * FROM ".$this->table);
	}

	public function save($data = null)
	{
		if(is_array($data)){
			$this->setFromArray($data);
		}
		$where = array();
		foreach ($this->primary as $key) {
			if(array_key_exists($key, $this->_data)){
				$where[$key] = $this->_data[$key];
			}
		}
		if(count($where) == count($this->primary)){
			//Update
			$this->_wrapper->update($this->table,$this->_data,$where,$this->timestampable);
		}
		else{
			//Insert
			$this->_data[$this->primary[0]] = $this->_wrapper->insert($this->table,$this->_data,$this->timestampable);
		}
	}
}