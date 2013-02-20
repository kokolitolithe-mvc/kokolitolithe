<?php

class Translator{
	protected static $_instance = null;
	protected $locale;
	protected $data;

	public static function getInstance()
	{
		if (self::$_instance === null) {
			$classe = __CLASS__;
            self::$_instance = new $classe();
        }

        return self::$_instance;
	}

	public function setLocale($locale){
		$locale = explode("_", $locale);
		$this->locale = $locale[0];
	}

	public function getLocale(){
		return $this->locale;
	}

	public function setData($translate){
		$this->data = $translate;
	}

	public function getData(){
		return $this->data;
	}

	public function translate($key){
		if(empty($this->data)){
			throw new Exception("Le fichier de traduction n'a pas été chargé.", 1);
		}
		
		if(empty($this->locale)){
			throw new Exception("la locale n'a pas été définie", 1);
			
		}

		if(!array_key_exists($key,$this->data[$this->locale])){
			throw new Exception("La clé $key n'est pas définie dans le tableau de traduction pour la locale ".$this->locale, 1);
		}

		return $this->data[$this->locale][$key];
	}
}