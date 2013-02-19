<?php

class Mo_Bootstrap{
	protected static $_bootstrap = null;

	static public function getInstance(){
		if(self::$_bootstrap == null){
			// $class = __CLASS__;
			self::$_bootstrap = new Bootstrap();
		}
		return self::$_bootstrap;
	}

	public function initSession()
	{
		session_start();
	}

	public function initConfig()
	{
		$config = include(BASEPATH."/config/application.php");
		Registry::getInstance()->set("config",$config);
	}

	public function initDb()
	{
		$db = DataBase::instance();
		$config = Registry::getInstance()->get("config");
		
		$db->configMaster($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);

		$db->configSlave($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);
	}
}