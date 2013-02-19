<?php
interface Cache_Encode_Interface{
	public function encode($decode);
    public function decode($encode);
}