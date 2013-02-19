<?php
/**
 * Moufasa
 * Class Layout
 */
class Layout{
    protected $styles = array();
    protected $scripts = array();
    protected $content = array();
    protected $captureStyle = array();
    protected $captureScript = array();

    protected $description;
    protected $title;
    public $data;

    protected $defaultView = array('footer','header','menu','left','right');

    public function __construct($data){
        $this->data = $data;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }


    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addTitle($title, $seperator = " - ")
    {
        $this->title .= $seperator.$title;
    }

    public function addStyle($path){
        $this->styles[] = $path;
        return $this;
    }

   public function addScript($path){
        $this->scripts[] = $path;
        return $this;
    }

    public function setContent($contener, $content){
        $this->content[$contener] = $content;
    }

    public function styles()
    {
        $affiche = "";
        foreach ($this->styles as $style) {
            $affiche .= "<link href=\"/css/".$style."\" rel=\"stylesheet\">\n";
        }
        foreach ($this->captureStyle as $style) {
            $affiche .= $style."\n";
        }
        return $affiche;
    }


    public function scripts()
    {
        $affiche = "";
        foreach ($this->scripts as $script) {
            $affiche .= "<script src=\"/js/".$script."\"></script>\n";
        }
        foreach ($this->captureScript as $script) {
            $affiche .= $script."\n";
        }

        return $affiche;
    }

    public function getContent($contener = "body")
    {
        return $this->content[$contener];
    }

    public function render()
    {
        foreach ($this->defaultView as $view) {
            if($this->checkView($view)){
                ob_start();
                include("../views/layout/$view.phtml");
                $this->setContent($view, ob_get_contents());
                ob_end_clean();
            }
        }

        include("../views/layout.phtml");
    }

    public function checkView($pathName){
        if(file_exists("../views/layout/$pathName.phtml")){
            return true;
        }else{
            return false;
        }        
    }

    public function captureStyleStart()
    {
         ob_start();
    }

    public function captureStyleStop()
    {
        $this->captureStyle[] = ob_get_contents();
        ob_end_clean();
    }

    public function captureScriptStart()
    {
         ob_start();
    }

    public function captureScriptStop()
    {
        $this->captureScript[] = ob_get_contents();
        ob_end_clean();
    }
}