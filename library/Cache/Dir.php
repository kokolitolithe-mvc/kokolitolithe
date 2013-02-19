<?php
class Cache_Dir{
	protected $baseDir;
	protected $entities;
	protected $encoder;

	public function __construct($dir,$encoder = null){
		// Vérifie la syntaxe du chemin vers le dossier de cache puis le stocke
		$dir = $this->syntaxDossier($dir);
		$this->baseDir = $dir;

		// Stocke l'encodeur
		if(is_null($encoder) or !($encoder instanceof Cache_Encode_Interface)){
			$encoder = new Cache_Encode_MsgPack();
		}
		$this->encoder = $encoder;
	}

	public function save(){
		// Vérifie que le dossier de stockage du cache existe, sinon on le crée
		if(!($this->dossierExist($this->baseDir))){
			try{
				if(!mkdir($this->baseDir,0777)){
					throw new Exception("Impossible de créer le dossier ".$this->baseDir, 1);
				}
			}
			catch(Exeption $e){
				echo $e->getErrorMessage();
			}
		}

		// Pour chaque entitée
		foreach($this->entities as $entity){
			// On définit le dossier d'enregistrement
			$path = $this->baseDir.$entity->getName()."/";

			// Si le dossier existe, on le supprime afin de faire place au nouvelle enregistrement
			if($this->dossierExist($path)){
				$this->clearDossier($path);
			}
			
			// On crée le dossier d'enregistrement
			try{
				if(!mkdir($path,0777)){
					throw new Exception("Impossible de créer le dossier ".$this->baseDir, 1);
				}
			}
			catch(Exeption $e){
				echo $e->getErrorMessage();
			}

			// Pour chacune des données
			foreach($entity->getAllData() as $cacheData){
				try{
					$this->saveFile($this->encoder->encode($cacheData->getData()),$path,$cacheData->getName());
				}
				catch(Exeption $e){
					echo $e->getErrorMessage();
				}
			}
		}
	}

	public function fetchAll(){
		// Initialisation de notre variable de sortie
		$arrayCacheEntity = array();

		// Scan le cache à la recherche des différentes entitées
		try{
			$entities = scandir($this->baseDir);
			if($entities == false)
				throw new Exception("Le dossier ".$this->baseDir." n'existe pas", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}

		// Pour chacune des entitées, on stocke le cacheData
		foreach($entities as $entity)
		{
			if($entity != "." AND $entity != ".."){ 
				$arrayCacheEntity[$entity] = $this->find($entity);
			}
		}
		return $arrayCacheEntity;
	}
		
	public function find($entity,$params = NULL){
		// On défini le chemin vers l'entitée
		$path = $this->baseDir.$entity."/";

		// On vérifie que le chemin existe bien, sinon on retourne false
		if(!($this->dossierExist($path)))
			return false;

		if($params == NULL)
			return $this->findAll($path);
		if(!is_array($params))
			return $this->findId($path,$params);
		if(is_array($params))
			return $this->findBy($path,$params);
		else{
			try{
				throw new Exception("Paramètres incorrects", 1);
			}
			catch(Exeption $e){
				echo $e->getErrorMessage();
			}
		}
		return false;
	}

	protected function findAll($path){
		// Initialisation de notre variable de sortie
		$arrayCacheData = array();

		// Scan l'entitée à la recherche des différents fichiers de données
		try{
			$files = scandir($path);
			if($files == false)
				throw new Exception("Le dossier ".$path." n'existe pas", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}

		// Pour chacun des fichiers de données, on stocke leur contenu
		foreach ($files as $file)
		{
			if ($file != "." AND $file != ".."){
				try{
					$handle = fopen($path.$file, "rb");
					if($handle == false)
						throw new Exception("Impossible d'ouvrir le fichier ".$path.$file, 1);
					if($content = fread($handle, filesize($path.$file))){
						$cacheData = $this->encoder->decode($content);
					}
					else
						throw new Exception("Impossible de lire le contenu du fichier ".$path.$file, 1);
				}
				catch(Exeption $e){
					echo $e->getErrorMessage();
				}
				fclose($handle);

				$arrayCacheData[] = $cacheData;
			}
		}
		if(!empty($arrayCacheData))
			return $arrayCacheData;
		else
			return false;
	}

	protected function findId($path,$id){
		// On défini le chemin vers l'entitée
		$file = $path.$id.".cache";
		
		// On vérifie que le fichier existe bien, sinon on retourne false
		if(!($this->fileExist($file)))
			return false;
		
		try{
			$handle = fopen($file, "rb");
			if($handle == false)
				throw new Exception("Impossible d'ouvrir le fichier ".$file, 1);
			if($content = fread($handle, filesize($file))){
				$cacheData = $this->encoder->decode($content);
			}
			else
				throw new Exception("Impossible de lire le contenu du fichier ".$file, 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}
		fclose($handle);
		return $cacheData;
	}

	protected function findBy($path,$params){
		// Initialisation de notre variable de sortie
		$arrayCacheData = array();

		// Scan l'entitée à la recherche des différents fichiers de données
		try{
			$files = scandir($path);
			if($files == false)
				throw new Exception("Le dossier ".$path." n'existe pas", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}

		if(!isset($params["id"])){
			// Pour chacun des fichiers de données, on stocke leur contenu
			foreach ($files as $file)
			{
				if($file != "." AND $file != ".."){
					try{
						$handle = fopen($path.$file, "rb");
						if($handle == false)
							throw new Exception("Impossible d'ouvrir le fichier ".$path.$file, 1);
						if($content = fread($handle, filesize($path.$file))){
							$cacheData = $this->encoder->decode($content);
						}
						else
							throw new Exception("Impossible de lire le contenu du fichier ".$path.$file, 1);
					}
					catch(Exeption $e){
						echo $e->getErrorMessage();
					}
					fclose($handle);
					$arrayCacheData[] = $cacheData;
				}
			}
		}
		else{
			$arrayCacheData[] = $this->findId($path,$params["id"]);
		}

		foreach($arrayCacheData as $id => $cacheData){
			foreach($params as $param => $value){
				if(!preg_match("#".$value."#",$cacheData[$param]))
					unset($arrayCacheData[$id]);
			}
		}
		
		if(!empty($arrayCacheData))
			return $arrayCacheData;
		else
			return false;
	}

	public function setBaseDir($dir){
		$dir = $this->syntaxDossier($dir);
		$this->baseDir = $dir;
	}

	public function getBaseDir(){
		return $this->baseDir;
	}

	public function setEntities($entities){
		foreach($entities as $name => $datas){
			$entity = new Cache_Entity();
			$entity->setName($name);
			$entity->setFromArray($datas);
			$this->entities[$entity->getName()] = $entity;
		}
	}

	public function addEntity(Cache_Entity $entity){
		try{
			if($entity instanceof Cache_Entity)
				throw new Exception("Le paramètre n'est pas une instance de Cache_Entity", 1);
			$this->entities[$entity->getName()] = $entity;
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}
	}

	public function getEntities(){
		return $this->entities;
	}

	public function getEntity(Cache_Entity $entity){
		return $this->entities[$entity->getName()];
	}

	protected function syntaxDossier($dir){
		if(!(preg_match("#/$#", $dir))){
			$dir = $dir."/";
		}
		return $dir;
	}

	public function clearDossier($dir){
		$this->syntaxDossier($dir);
		try{
			if(is_dir($dir)){
				$objects = scandir($dir);
				if ($objects == false)
					throw new Exception("Le dossier ".$dir." n'existe pas", 1);
				foreach ($objects as $object){ 
					if ($object != "." AND $object != ".."){ 
						if (filetype($dir.$object) == "dir"){
							$this->clearDossier($dir.$object."/");
						}
						else{
							if(!unlink($dir.$object))
								throw new Exception("Le fichier ".$dir.$object." n'a pas pu être supprimé", 1);
						} 
					} 
				}
				if(!reset($objects))
					throw new Exception("La lecture des fichiers du dossier ".$dir." n'a pas pu repartir du premier fichier", 1);
				if(!rmdir($dir))
					throw new Exception("Le dossier ".$dir." n'a pas pu être supprimé", 1);
			}
			else
				throw new Exception("Le dossier ".$dir." n'existe pas", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}
	}

	public function clearFile($file){
		if(file_exists($file)){
			if(!unlink($file))
				throw new Exception("Le fichier ".$file." n'a pas pu être supprimé", 1);
		}
	}

	protected function dossierExist($dir){
		return is_dir($dir);
	}

	protected function fileExist($file){
		return is_file($file);
	}

	protected function saveFile($cacheData, $dir, $name){
		$file = fopen($dir.$name.".cache", "w+");
		if($file == false){
			throw new Exception("Impossible d'ouvrir le fichier". $dir.$name.".cache", 1);
		}
		fwrite ($file,$cacheData);
		fclose($file);
	}
}