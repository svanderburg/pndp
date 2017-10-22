<?php
namespace Pkgs\InstanceToXML;

class DataClass
{
	public $hello;

	public $message;

	public function __construct()
	{
		$this->hello = "Hello world!";
		$this->message = array("PNDP", "is", "cool");
	}
}
?>
