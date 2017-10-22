<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix of if-then-else statement.
 */
class NixIf extends NixBlock
{
	public $ifExpr;

	public $thenExpr;

	public $elseExpr;

	/**
	 * Creates a new NixIf instance.
	 *
	 * @param mixed $ifExpr An object representing an expression evaluating to a boolean
	 * @param mixed $thenExpr Expression that gets evaluated if the condition is true
	 * @param mixed $elseExpr Expression that gets evaluated if the condition is false
	 */
	public function __construct($ifExpr, $thenExpr, $elseExpr)
	{
		$this->ifExpr = $ifExpr;
		$this->thenExpr = $thenExpr;
		$this->elseExpr = $elseExpr;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		return "if ".NixGenerator::phpToIndentedNix($this->ifExpr, $indentLevel, $format).
			" then ".NixGenerator::phpToIndentedNix($this->thenExpr, $indentLevel, $format).
			" else ".NixGenerator::phpToIndentedNix($this->elseExpr, $indentLevel, $format);
	}
}
?>
