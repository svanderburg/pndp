<?php
namespace PNDP\AST;

class NixURL extends NixValue
{
	public function __construct($value)
	{
		parent::__construct($value);
	}

	public function toNixExpr($indentLevel, $format)
	{
		if(strpos($this->value, '#') === false)
			return $this->value;
		else
			return '"'.$this->value.'"';
	}
}
?>
