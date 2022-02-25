<?php
namespace Pkgs;

class SayHello
{
	public static function composePackage(object $args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "sayhello",
			"buildCommand" => 'echo "Hello world!" > $out'
		));
	}
}
?>
