<?php
/**
* Manager de l'api publispeak
*/
class Model_Api extends Http_Client_Rest
{
	static protected $_instance = null;
	public static function getInstance(){
		if(self::$_instance == null)
			self::$_instance = new Model_Api();
		return self::$_instance;
	}

}