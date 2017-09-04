<?php
namespace PNDP\AST;

class NixExpression extends NixValue
{
	public function __construct($value)
	{
		parent::__construct($value);
	}

	public function toNixExpr($indentLevel, $format)
	{
		return $this->value;
	}
}
?>
