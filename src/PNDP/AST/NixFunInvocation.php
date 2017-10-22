<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix function invocation consisting of an
 * expression yielding a function definition and an expression capturing the
 * parameter.
 */
class NixFunInvocation extends NixBlock
{
	public $funExpr;

	public $paramExpr;

	/**
	 * Creates a new NixFunInvocation instance.
	 *
	 * @param mixed $funExpr An object representing an expression that yields a function definition
	 * @param mixed $paramExpr An object representing an expression that yields the function parameter
	 */
	public function __construct($funExpr, $paramExpr)
	{
		$this->funExpr = $funExpr;
		$this->paramExpr = $paramExpr;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		/* Generate the sub expression that yields the function definition */
		$funExprStr = NixGenerator::phpToIndentedNix($this->funExpr, $indentLevel, $format);

		if($this->funExpr instanceof NixBlock && !($this->funExpr instanceof NixFunInvocation)) // Some objects require ( ) around them to make them work
			$funExprStr = $this->funExpr->wrapInParenthesis($funExprStr);

		/* Generate the sub expression that yields the parameter */
		$paramExprStr = NixGenerator::phpToIndentedNix($this->paramExpr, $indentLevel, $format);

		if($this->paramExpr instanceof NixBlock) // Some objects require ( ) around them to make them work
			$paramExprStr = $this->paramExpr->wrapInParenthesis($paramExprStr);

		/* Return the generated sub expression */
		return $funExprStr." ".$paramExprStr;
	}
}
?>
