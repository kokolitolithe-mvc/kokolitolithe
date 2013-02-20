<?php
class index extends Controller{

    public function indexAction(){
    	// var_dump(array("lorem" => 'ipsum'));
    	//Debug::Dump();
    	$test = new Model_Sommaire();
    	// $test->where = "rest";
    	// $test->url = "http://api.publispeak.com/document/36");
        // var_dump($test->find("1"));
        // var_dump($test->findBy(array("content" => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.")));
        // var_dump($test->findBy(array("content" => "New Page 4")));
    	// var_dump($test->findBy(array("content" => "fzfrge")));
        $array = $test->fetchAll();   
        $array = array();
        $array[] = array('id' => 5, 'content' => 'New Page 5');
        $test->save($array);
        Debug::dump($test->fetchAll());

        $this->loadView("index/index");
    }

    public function testAction(){
        // $page = new Cache_Entity("page");
        // $cache = new Model_Data_Cache();
        // $cacheDir = new Cache_Dir($path,$encoder);
        // $cache->addEntity($page);
    }
}
