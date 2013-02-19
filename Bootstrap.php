<?php

/**
* bootstrap
* toute les methodes public initXXX (ou XXX peut être n'importe quoi) seront appelée
*/
class Bootstrap extends Mo_Bootstrap
{
	public function initCacheDir(){
        // On récupère l'id du document
        
        $document_id = 7;
        $path = __DIR__."/Cache/".$document_id."/";
        Registry::getInstance()->set("Cache_Manager",new Cache_Dir($path,new Cache_Encode_Json()));
    }
}