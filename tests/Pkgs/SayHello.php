<?php
namespace Pkgs;

class SayHello
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "sayhello",
			"buildCommand" => 'echo "Hello world!" > $out'
		));
	}
}
?>
