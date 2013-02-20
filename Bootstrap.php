<?php

/**
* bootstrap
* toute les methodes public initXXX (ou XXX peut être n'importe quoi) seront appelée
*/
class Bootstrap extends Mo_Bootstrap
{
	public function initCacheDir(){
        $params = Application::getInstance()->getParams();
        $path = __DIR__."/Cache/".$params["document_id"]."/";
        Registry::getInstance()->set("Cache_Manager",new Cache_Dir($path,new Cache_Encode_Json()));
    }

    public function initTranslate(){
        Registry::getInstance()->set("translate",include("config/translate.php"));
        Registry::getInstance()->set("locale","fr");
    }

    public function CustomRoute(){
       $route = new Route( '/document_id/:class/:method' );

       $route->addDynamicElement( 'document_id', 'document_id' )
             ->addDynamicElement( ':class', ':class' )
             ->addDynamicElement( ':method', ':method' );

       Application::getInstance()->getRouter()->addRoute( 'document-index', $route );

       // -------------------------------------------------- //

       // $route = new Route( '/document_id/:class/:method' );
       // $route->addDynamicElement( 'document_id', 'document_id' )
       //       ->addDynamicElement( ':class', ':class' )
       //       ->addDynamicElement( ':method', ':method' );

       // Application::getInstance()->getRouter()->addRoute( 'document-index', $route );

    }
}