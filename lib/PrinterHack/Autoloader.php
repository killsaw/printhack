<?php

namespace PrinterHack;

class Autoloader
{
	public static function register()
	{
		spl_autoload_register('self::loadClass');
	}
	
	public static function loadClass($class)
	{
		$class_file = sprintf('%s/%s.php',
						dirname(__DIR__),
						preg_replace('/[\\\\_]/', '/', $class)
					  );
		if (file_exists($class_file)) {
			require_once $class_file;
			return true;
		}
		return false;
	}
}
