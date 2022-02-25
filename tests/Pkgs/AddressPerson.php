<?php
namespace Pkgs;
use PNDP\AST\NixNoDefault;
use PNDP\AST\NixFunction;
use PNDP\AST\NixFunInvocation;

class AddressPerson
{
	public static function composePackage(object $args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "addressPerson",
			"message" => new NixFunInvocation(new NixFunction(array(
				"firstName" => new NixNoDefault(),
				"lastName" => new NixNoDefault(),
				"prefix" => "Dear"
			), '${prefix} ${firstName} ${lastName}'), array(
				"firstName" => "Sander",
				"lastName" => "van der Burg"
			)),
			"buildCommand" => 'echo $message > $out'
		));
	}
}
?>
