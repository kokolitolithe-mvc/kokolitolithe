<?php
class Controller{
    public $user, $title, $action;
    public $data;
    public $layout = true;
    public $params = array();
    public $translator = false;

    public function __construct()
    {
       if($this->layout == true and !$this->layout instanceof Layout){
            $this->layout = new Layout($this->data);
        }

        if(Registry::getInstance()->offsetExists("translate")){
            $translator = Translator::getInstance();
            $translator->setData(Registry::getInstance()->get("translate"));
            $translator->setLocale(Registry::getInstance()->get("locale"));
            $this->translator = $translator;
        }

        $this->setParams(Application::getInstance()->getParams());

        if(method_exists($this,'init')){
            $this->init();
        }
    }

    public function loadView($pathName, $placeholder = 'body', $return = false){
        if($this->checkView($pathName)){
			if($return)
            {
				ob_start();
                include("../views/$pathName.phtml");
                $retour = ob_get_contents();
                ob_end_clean();
                return $retour;
			}
            elseif($this->layout)
            {
                if(!$this->layout instanceof Layout){
                    $this->layout = new Layout($this->data);
                }
                ob_start();
                include("../views/$pathName.phtml");
                $this->layout->setContent($placeholder, ob_get_contents());
                ob_end_clean();
                $this->layout->render();
            }
            else
            {
            	include("../views/$pathName.phtml");
			}
        }else{
			if($return){
				return false;
			}else{
            	self::f404Static();
			}
        }
    }

    public function checkView($pathName){
        if(file_exists("../views/$pathName.phtml")){
            return true;
        }else{
            return false;
        }
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
    public function hasParams($key)
    {
        return !empty($this->params[$key]);
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function getTranslator(){
        if($this->translator == false){
            throw new Exception("Aucun Traducteur n'a été définie", 1);
        }
        return $this->translator;
    }

    public function translate($key){
        return $this->getTranslator()->translate($key);
    }
}