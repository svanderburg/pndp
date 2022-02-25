<?php
namespace Pkgs;
use PNDP\AST\NixAssert;
use PNDP\AST\NixExpression;
use PNDP\AST\NixIf;
use PNDP\AST\NixLet;

class Conditionals
{
	public static function composePackage(object $args)
	{
		return new NixAssert(true, new NixLet(array(
			"test" => true
		), $args->writeTextFile(array(
			"name" => "conditionals",
			"text" => new NixIf(new NixExpression("test"), "It is true!", "It is false!")
		))));
	}
}
?>
