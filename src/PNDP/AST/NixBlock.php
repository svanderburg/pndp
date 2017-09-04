<?php
namespace PNDP\AST;

abstract class NixBlock extends NixObject
{
	public function wrapInParenthesis($expr)
	{
		return "(".$expr.")";
	}
}
?>
