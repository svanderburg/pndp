<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * A utility class that forces an array to manifest itself as an attribute set.
 */
class NixAttrSet extends NixValue
{
	/**
	 * Creates a new NixAttrSet instance.
	 *
	 * @param array $value An array that should be represented as an attribute set
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
		return NixGenerator::associativeArrayToIndentedNix($this->value, $indentLevel, $format);
	}
}
?>
