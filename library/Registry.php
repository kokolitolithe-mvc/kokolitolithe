<?php
class Registry extends ArrayObject
{
	protected static $_registry = null;

	public static function getInstance()
	{
		if (self::$_registry === null) {
            self::$_registry = new Registry();
        }

        return self::$_registry;
	}
 
    public static function _unsetInstance()
    {
        self::$_registry = null;
    }

    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }

    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            throw new Exception("Illegal offset $index.");
        }

        return $instance->offsetGet($index);
    }

    public static function isRegistered($index)
    {
        if (self::$_registry === null) {
            return false;
        }
        return self::$_registry->offsetExists($index);
    }

    public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }

    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }
}