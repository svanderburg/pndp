<?php
namespace Pkgs;
use PNDP\AST\NixExpression;
use PNDP\AST\NixFunInvocation;

class WriteTextFile
{
	public static function composePackage(object $args, array $funArgs)
	{
		return new NixFunInvocation(new NixExpression("pkgs.writeTextFile"), $funArgs);
	}
}
?>
