<?php
namespace PNDP\AST;

/**
 * Creates a Nix value object that captures common properties of value objects
 * in the Nix expression language, for which is no equivalent PHP object
 * available.
 */
abstract class NixValue extends NixObject
{
	public $value;

	/** Creates a new NixValue instance.
	 *
	 * @param mixed $value Value of the reference
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}
}
?>
