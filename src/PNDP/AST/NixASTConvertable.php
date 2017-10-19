<?php
namespace PNDP\AST;

/**
 * This interface should be implemented by any class whose object instances are
 * supposed to be converted into a Nix expression.
 */
interface NixASTConvertable
{
	/**
	 * Returns a compound object that will be converted into a Nix
	 * expression.
	 *
	 * @return mixed A composition of objects that can be converted into a Nix expression
	 */
	public function toNixAST();
}
?>
