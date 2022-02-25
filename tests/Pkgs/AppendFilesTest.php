<?php
namespace Pkgs;
use PNDP\AST\NixFile;

class AppendFilesTest
{
	public static function composePackage(object $args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "appendFiles",

			"appendFileA" => new NixFile("./appendFileA/text.txt", __DIR__),
			"appendFileB" => new NixFile("./append File B/text.txt", __DIR__),

			"buildCommand" => 'cat $appendFileA $appendFileB > $out'
		));
	}
}
?>
