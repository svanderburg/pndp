<?php
namespace Pkgs;
use PNDP\AST\NixExpression;

class Perl
{
	public static function composePackage($args)
	{
		return new NixExpression("pkgs.perl");
	}
}
?>
