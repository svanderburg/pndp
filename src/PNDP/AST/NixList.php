<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

class NixList extends NixValue
{
	public function __construct(array $value)
	{
		parent::__construct($value);
	}

	public function toNixExpr($indentLevel, $format)
	{
		return NixGenerator::sequentialArrayToIndentedNix($this->value, $indentLevel, $format);
	}
}
?>
