<?php
abstract class Model_Abstract{
    protected $_data = array();
	protected $url = "";

	public function __set($name, $value)
	{
		 $this->_data[$name] = $value;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->_data)) {
			throw new Exception("l'attribut $name n'existe pas.", 1);
        }
        return $this->_data[$name];
	}

	public function setFromArray($data)
	{
	    foreach ($data as $columnName => $value) {
		   	$this->__set($columnName, $value);
	    }
	}

	public function toArray()
	{
		return $this->_data;
	}

    public function needRest($return){

    }

    protected function readRest($type,$datas){
        // $client = new Http_Client_Rest();
        // $client->auth('webapp','786453121');
        // if($type != NULL){
        //     if($type == "pages")
        //         $url = $this->_data["url"].".json";
        //     else
        //         $url = $this->_data["url"]."/".$type."/";
        //     if(!empty($datas)){
        //         if(!is_array($datas)){
        //             $url2 = $url.$datas.".json";
        //             $response = $client->get($url2);
        //             $responseArray[] = json_decode($response->body,true);
        //         }
        //         return $responseArray;
        //     }
        //     $response = $client->get($url);
        //     return json_decode($response->body,true);
        // }
        // else{
        //     $response = $client->get($this->url);
        //     return json_decode($response->body,true);
        // }
    }

	abstract public function find($id);
	abstract public function findBy(array $params);
	abstract public function fetchAll();
	abstract public function save($data = null);
}