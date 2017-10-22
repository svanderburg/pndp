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
	 * @param array $value An array that should be represented as a list
	 */
	public function __construct(array $value)
	{
		parent::__construct($value);
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		return NixGenerator::sequentialArrayToIndentedNix($this->value, $indentLevel, $format);
	}
}
?>
