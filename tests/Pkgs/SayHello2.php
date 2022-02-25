<?php
namespace Pkgs;
use PNDP\AST\NixWith;

class SayHello2
{
	public static function composePackage(object $args)
	{
		return new NixWith(array(
			"firstName" => "Sander",
			"lastName" => "van der Burg"
		), $args->stdenv->mkDerivation(array(
			"name" => "sayHello2",
			"buildCommand" => 'echo ${firstName} ${lastName} > $out'
		)));
	}
}
?>
