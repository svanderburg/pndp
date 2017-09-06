<?php
class Pkgs
{
	public $stdenv;

	public function __construct()
	{
		$this->stdenv = new Pkgs\Stdenv();
	}

	public function fetchurl($args)
	{
		return Pkgs\FetchURL::composePackage($this, $args);
	}

	/* Manual composition of a package */
	public function hello()
	{
		return Pkgs\Hello::composePackage($this);
	}

	public function writeTextFile($args)
	{
		return Pkgs\WriteTextFile::composePackage($this, $args);
	}

	/* Auto composition of arbitrary packages */
	public function __call($name, $arguments)
	{
		$className = ucfirst($name);
		$methodName = 'Pkgs\\'.$className.'::composePackage';
		return $methodName($this);
	}
}
?>
