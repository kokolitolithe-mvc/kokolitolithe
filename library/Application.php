<?php
function __autoload($class_name){
	$className = explode('_', $class_name);
	$path = "";
	foreach($className as $key => $val){
		$path .= $val."/";
	}
	$path = substr($path, 0, strlen($path)-1);
    require_once($path.".php");
}

require_once 'Router/php-router.php';

class Application{
	protected $_router = null;
	protected $_dispatcher = null;
	protected $_params = null;

	protected static $_instance = null;

	public static function getInstance()
	{
		if (self::$_instance === null) {
			$classe = __CLASS__;
            self::$_instance = new $classe();
        }

        return self::$_instance;
	}

	public function __construct(){
		$this->_router = new Router;
		$this->_dispatcher = new Dispatcher;
	}

	public function getRouter(){
		return $this->_router;
	}
	public function getDispatcher(){
		return $this->_dispatcher;
	}
	public function run(){
		$this->callRoute();
		
		try {
		  $found_route = $this->getRouter()->findRoute( urldecode($_SERVER['REQUEST_URI']) );
		  $method = $found_route->getMapMethod();
		  $method .= "Action"; //on rajoute le suffixe Action
		if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			//on ajoute le prefix ajax_ 
			$method = "ajax_".$method;	
		}		  
		  $found_route->setMapMethod($method);
		  
		  $this->_params = $found_route->getMapArguments();
		  $this->callbootstrap();
		  $this->getDispatcher()->dispatch( $found_route );

		} catch ( RouteNotFoundException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( badClassNameException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( classFileNotFoundException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( classNameNotFoundException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( classMethodNotFoundException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( classNotSpecifiedException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		} catch ( methodNotSpecifiedException $exception ) {
		  //handle Exception
			Debug::dump($exception);
		}
	}

	/*
	instancie la class boostrap et appelle toute les methodes initXXXX()
	*/
	public function callbootstrap(){
		//TODO : Créer une classe boostrap dans la librairie, y les traitements d'init de route, bdd, session, and co
		// exit;$tb = new Mo_Boostrap();exit;
		$t = get_class_methods("Bootstrap");
		$boostrap = Bootstrap::getInstance();
		foreach ($t as $method) {
			if(substr($method, 0, 4) == "init"){
				$boostrap->$method();
			}
		}
	}

	public function callRoute(){
		// Mise en place des routes par default
		$this->setDefaultRoute();
		//recuperer les routes Customs
	}

	public function getParams(){
		return $this->_params;
	}

	protected function setDefaultRoute()
	{
		$controllerPath = dirname(__FILE__)."/../controllers/";
		$this->_dispatcher->setClassPath($controllerPath);
		
		$boostrap = Bootstrap::getInstance();
		if(method_exists($boostrap, "CustomRoute")){
			$boostrap->CustomRoute();
		}

		//Set up your default route:
		$default_route = new Route('/');
		$default_route->setMapClass('index')
					  ->setMapMethod('index');


		$this->_router->addRoute( 'default', $default_route );
		
		//Set up your default route:
		$default_action = new Route('/:class');
		$default_action->addDynamicElement( ':class', ':class' )->setMapMethod('index');
		$this->_router->addRoute( 'controller-default', $default_action );

		$route = new Route( '/:class/:method' );
		$route->addDynamicElement( ':class', ':class' )->addDynamicElement( ':method', ':method' );
		$this->_router->addRoute( 'controller-class', $route );


	}
}