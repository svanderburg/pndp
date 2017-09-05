<?php
namespace Pkgs;
use PNDP\AST\NixExpression;
use PNDP\AST\NixFunction;
use PNDP\AST\NixFunInvocation;

class AddressPersons
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "addressPersons",
			"message" => new NixFunInvocation(new NixFunction(array("persons", "prefix"), new NixExpression('map (person: "${prefix} ${person}\n") persons')), array(
				"persons" => array("Sander", "Eelco"),
				"prefix" => "Dear"
			)),
			"buildCommand" => 'echo $message > $out'
		));
	}
}
?>
