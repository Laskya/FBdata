<?php
function autoload($class)
{
	switch ($class)
	{

		case "Json":
			require('json.php');
			break;

		case "MyDB":
			require('mysqli.php');
			break;
	}
}

class Autoloader
{
	public static function autoload($class)
	{
		autoload($class);
	}
}

spl_autoload_register('autoload');
spl_autoload_register(array('autoloader','autoload'));
?>
