<?php
namespace PNDP\AST;

/**
 * A Nix object that contains an already generated Nix expression.
 */
class NixExpression extends NixValue
{
	/**
	 * Creates a new NixExpression instance.
	 *
	 * @param string $value Value containing a Nix expression
	 */
	public function __construct($value)
	{
		parent::__construct($value);
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		return $this->value;
	}
}
?>
