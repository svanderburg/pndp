<?php
namespace PNDP\AST;

/**
 * A Nix object that represents a reference to a file that resides in the Nix
 * store. This object is useful to directly refer to something that is in the
 * Nix store, without copying it.
 */
class NixStorePath extends NixFunInvocation
{
	/**
	 * Creates a new NixStorePath instance.
	 *
	 * @param $value A sub expression referring to a Nix store file
	 */
	public function __construct(string $value)
	{
		parent::__construct(new NixExpression("builtins.storePath"), $value);
	}
}
?>
