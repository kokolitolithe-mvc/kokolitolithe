<?php
class Cache_Encode_MsgPack implements Cache_Encode_Interface{
    public function encode($decode){
		try{
			$encode = msgpack_serialize($decode);
			if($encode == false)
				throw new Exception("L'élément suivant : '".$decode."'' n'a pas pu être encoder en MsgPack", 1);
		}
		catch(Exeption $e){
			$e->getErrorMessage();
		}
		return $encode;
	}
    public function decode($encode){
    	try{
			$decode = msgpack_unserialize($encode);
			if($decode == false)
				throw new Exception("L'élément suivant : '".$encode."'' n'a pas pu être décoder du MsgPack", 1);
		}
		catch(Exeption $e){
			$e->getErrorMessage();
		}
		return $decode;
    }
}