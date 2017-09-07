<?php
class Pkgs
{
	public $stdenv;

	public function __construct()
	{
		$this->stdenv = new Pkgs\Stdenv();
	}

	/* Manual compositions of packages */

	public function fetchurl($args)
	{
		return Pkgs\Fetchurl::composePackage($this, $args);
	}

	public function hello()
	{
		return Pkgs\Hello::composePackage($this);
	}

	/* Auto composition of arbitrary packages */
	public function __call($name, $arguments)
	{
		// Compose the classname from the function name
		$className = ucfirst($name);
		// Compose the name of the method to compose the package
		$methodName = 'Pkgs\\'.$className.'::composePackage';
		// Prepend $this so that it becomes the first function parameter
		array_unshift($arguments, $this);
		// Dynamically the invoke the class' composition method with $this as first parameter and the remaining parameters
		return call_user_func_array($methodName, $arguments);
	}
}
?>
