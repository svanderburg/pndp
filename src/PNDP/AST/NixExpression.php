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
	 * @param $value Value containing a Nix expression
	 */
	public function __construct(string $value)
	{
		parent::__construct($value);
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		return $this->value;
	}
}
?>
