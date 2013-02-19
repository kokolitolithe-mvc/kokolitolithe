<?php
class Cache_Encode_Json implements Cache_Encode_Interface{
	public function encode($decode){
		try{
			$encode = json_encode($decode,true);
			if($encode == false)
				throw new Exception("L'élément suivant : '".$decode."'' n'a pas pu être encoder en JSON", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}
		return $encode;
	}
    public function decode($encode){
    	try{
			$decode = json_decode($encode,true);
			if($decode == false)
				throw new Exception("L'élément suivant : '".$encode."'' n'a pas pu être décoder du JSON", 1);
		}
		catch(Exeption $e){
			echo $e->getErrorMessage();
		}
		return $decode;
    }
}