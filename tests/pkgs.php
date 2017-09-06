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

	public function writeTextFile($args)
	{
		return Pkgs\WriteTextFile::composePackage($this, $args);
	}

	public function stringWriteTest()
	{
		return Pkgs\StringWriteTest::composePackage($this);
	}

	public function appendFilesTest()
	{
		return Pkgs\AppendFilesTest::composePackage($this);
	}

	public function sayHello()
	{
		return Pkgs\SayHello::composePackage($this);
	}

	public function addressPerson()
	{
		return Pkgs\AddressPerson::composePackage($this);
	}

	public function addressPersons()
	{
		return Pkgs\AddressPersons::composePackage($this);
	}

	public function addressPersonInformally()
	{
		return Pkgs\AddressPersonInformally::composePackage($this);
	}

	public function sayHello2()
	{
		return Pkgs\SayHello2::composePackage($this);
	}

	public function objToXML()
	{
		return Pkgs\ObjToXML::composePackage($this);
	}

	public function conditionals()
	{
		return Pkgs\Conditionals::composePackage($this);
	}
}
?>
