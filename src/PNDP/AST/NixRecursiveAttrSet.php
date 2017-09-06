<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * A recursive attribute set in which members can refer to each other.
 */
class NixRecursiveAttrSet extends NixValue
{
	/**
	 * Creates a new NixRecursiveAttrSet instance.
	 *
	 * @param array $value An array that should be represented as a recursive attribute set
	 */
	public function __construct(array $value)
	{
		parent::__construct($value);
	}

	/**
	 * @see NixObject#toNixExpr
	 */
	public function toNixExpr($indentLevel, $format)
	{
		if(count($this->value) == 0)
			return "rec {}";
		else
			return "rec {\n".
				NixGenerator::objectMembersToAttrsMembers($this->value, $indentLevel + 1, $format).
				NixGenerator::generateIndentation($indentLevel, $format)."}";
	}
}
?>
