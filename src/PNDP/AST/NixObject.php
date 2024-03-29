<?php
namespace PNDP\AST;

/**
 * Creates a Nix object that captures properties of Nix expression language
 * constructs for which no PHP equivalent is available.
 */
abstract class NixObject
{
	/**
	 * Converts the Nix object to a string containing the equivalent Nix expression
	 *
	 * @param $indentLevel The indentation level of the resulting sub expression
	 * @param $format Indicates whether to nicely format to expression (i.e. generating whitespaces) or not
	 * @return String with the equivalent Nix expression
	 */
	public abstract function toNixExpr(int $indentLevel, bool $format): string;
}
?>
