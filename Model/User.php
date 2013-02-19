²<?php
/**
* Model_User
* @autors Moufasa
*/
class Model_User extends Model
{
	protected $table = "user";
	protected $timestampable = true;
	protected $primary = array('id');
}