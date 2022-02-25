<?php
namespace Pkgs\InstanceToXML;

class DataClass
{
	public string $hello;

	public array $message;

	public function __construct()
	{
		$this->hello = "Hello world!";
		$this->message = array("PNDP", "is", "cool");
	}
}
?>
