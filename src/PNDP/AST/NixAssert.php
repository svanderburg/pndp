<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix of an assert statement.
 */
class NixAssert extends NixBlock
{
	/** An object representing an expression evaluating to a boolean */
	public $conditionExpr;

	/** Expression that gets evaluated if the condition is true. If it is false, the evaluation aborts with an error */
	public $body;

	/**
	 * Creates a new NixAssert instance.
	 *
	 * @param $conditionExpr An object representing an expression evaluating to a boolean
	 * @param $body Expression that gets evaluated if the condition is true. If it is false, the evaluation aborts with an error.
	 */
	public function __construct($conditionExpr, $body)
	{
		$this->conditionExpr = $conditionExpr;
		$this->body = $body;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		return "assert ".NixGenerator::phpToIndentedNix($this->conditionExpr, $indentLevel, $format).";\n".
			NixGenerator::generateIndentation($indentLevel, $format).NixGenerator::phpToIndentedNix($this->body, $indentLevel, $format);
	}
}
?>
