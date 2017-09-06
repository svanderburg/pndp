<?php
namespace PNDP\AST;

/**
 * Creates a Nix block object that contains a sub expression that might need
 * parenthesis ( ) around them in certain contexts, such as a list.
 */
abstract class NixBlock extends NixObject
{
	/**
	 * Wraps an expression in a block within parenthesis.
	 *
	 * @param string $expr String containing a Nix expression block
	 * @return string The same expression within parenthesis
	 */
	public function wrapInParenthesis($expr)
	{
		return "(".$expr.")";
	}
}
?>
