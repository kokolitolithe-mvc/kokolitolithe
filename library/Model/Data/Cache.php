<?php
abstract class Model_Data_Cache extends Model_Abstract{
    protected $type = "";
    protected $dir = "";
    protected $where = "cache";

    public function find($id){
        if($this->where == "rest")
            return $this->readRest($this->type,$id);
        else
            return $this->readCache($this->type,$id);
    }

    public function findBy(array $params){
        if($this->where == "rest")
            return $this->readRest($this->type,$params);
        else
            return $this->readCache($this->type,$params);
    }

    public function fetchAll(){
        if($this->where == "rest")
            return $this->readRest($this->type,null);
        else
            return $this->readCache($this->type,null);
    }

    public function save($datas = null){
        if($this->where == "cache"){
            $this->deleteCache($this->type,$datas);
            $this->writeCache($datas);
        }
    }

    public function delete($datas = array()){
        if($this->where == "cache")
            $this->deleteCache($this->type,$datas);
    }

    protected function writeCache($datas){
        $entities[$this->type] = $datas;
		$this->manager->setEntities($entities);
		$this->manager->save();
    }

    protected function readCache($type,$datas){
        if($type == null)
            return $this->manager->fetchAll();
        else{
            return $this->manager->find($type,$datas);
        }
    }

    protected function deleteCache($type,$datas){
    	if($type == null)
			$this->manager->clearDossier($this->manager->getBaseDir());
		else{
            if(!empty($datas)){
                foreach($datas as $key => $data){
                    if(is_string($data)){
                        $this->manager->clearFile($this->manager->getBaseDir().$type."/".$data.".cache");
                    }
                    else{
                        $this->manager->clearFile($this->manager->getBaseDir().$type."/".$key.".cache");
                    }
                }
            }
            else
                $this->manager->clearDossier($this->manager->getBaseDir().$type."/");
        }
    }
}
