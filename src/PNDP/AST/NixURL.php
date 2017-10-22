<?php
namespace PNDP\AST;

/**
 * A Nix URL object that gets validated by the Nix expression evaluator.
 */
class NixURL extends NixValue
{
	/**
	 * Creates a new NixURL instance.
	 *
	 * @param string $value Value containing a URL
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
		if(strpos($this->value, '#') === false)
			return $this->value;
		else
			return '"'.$this->value.'"';
	}
}
?>
