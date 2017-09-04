<?php
namespace Pkgs;
use PNDP\AST\NixFunInvocation;
use PNDP\AST\NixExpression;

class Stdenv
{
	public function mkDerivation($args)
	{
		return new NixFunInvocation(new NixExpression("pkgs.stdenv.mkDerivation"), $args);
	}
}
?>
