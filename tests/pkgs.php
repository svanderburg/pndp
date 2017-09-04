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

	public function hello()
	{
		return Pkgs\Hello::composePackage($this);
	}

	public function zlib()
	{
		return Pkgs\Zlib::composePackage($this);
	}

	public function perl()
	{
		return Pkgs\Perl::composePackage($this);
	}

	public function openssl()
	{
		return Pkgs\OpenSSL::composePackage($this);
	}

	public function curl()
	{
		return Pkgs\Curl::composePackage($this);
	}

	public function sayHello()
	{
		return Pkgs\SayHello::composePackage($this);
	}
}
?>
