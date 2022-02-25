<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * A utility class that forces an array to manifest itself as a list.
 */
class NixList extends NixValue
{
	/**
	 * Creates a new NixAttrSet instance.
	 *
	 * @param $value An array that should be represented as a list
	 */
	public function __construct(array $value)
	{
		parent::__construct($value);
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		return NixGenerator::sequentialArrayToIndentedNix($this->value, $indentLevel, $format);
	}
}
?>
