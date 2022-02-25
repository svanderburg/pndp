<?php
namespace PNDP\AST;

/**
 * A Nix object that imports an external Nix expression file.
 */
class NixImport extends NixFunInvocation
{
	/**
	 * Creates a new NixImport instance.
	 *
	 * @param $value A sub expression referring to an external Nix expression file
	 */
	public function __construct($value)
	{
		parent::__construct(new NixExpression("import"), $value);
	}
}
?>
