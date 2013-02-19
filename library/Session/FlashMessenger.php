<?php
class Session_FlashMessenger
{
	const NAMESPACE_MVC = "__FRAMEWORK";
	const NAMESPACE_FM = "flashMessenger";
	protected static $_instance = null;

	public function __construct()
	{
		$id = session_id();
		if(empty($id))
		{
			session_start();
		}
		if(!isset($_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM]))
		{
			$_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM] = array();
		}
	}
	
	public static function getInstance()
	{
		if (self::$_instance === null) {
            self::$_instance = new Session_FlashMessenger();
        }
        return self::$_instance;
	}

	public function setMessage($message, $lvl = "info")
	{
		$_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM][$lvl][] = $message;
	}

	public function getFm($lvl = null)
	{
		if($lvl == null){
			$retour = $_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM];
			unset($_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM]);
		}
		elseif(!isset($_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM][$lvl])){
			throw new Exception("le niveau $lvl n'est pas défini dans le FlashMessenger", 1);
		}
		else{
			$retour = $_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM][$lvl];
			unset($_SESSION[self::NAMESPACE_MVC][self::NAMESPACE_FM][$lvl]);
		}
		return $retour;
	}

	public function setError($message)
	{
		$this->setMessage($message,"error");
	}

	public function setWarning($message)
	{
		$this->setMessage($message,"warning");
	}

	public function setSuccess($message)
	{
		$this->setMessage($message,"success");
	}

	public function setInfo($message)
	{
		$this->setMessage($message,"info");
	}

	public function getAllMessages()
	{
		return $this->getFm();
	}

	public function getError()
	{
		$this->getFm("error");
	}

	public function getWarning()
	{
		$this->getFm("warning");
	}

	public function getSuccess()
	{
		$this->getFm("success");
	}

	public function getInfo()
	{
		$this->getFm("info");
	}
}