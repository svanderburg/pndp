<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

class NixFunInvocation extends NixBlock
{
	public $funExpr;

	public $paramExpr;

	public function __construct($funExpr, $paramExpr)
	{
		$this->funExpr = $funExpr;
		$this->paramExpr = $paramExpr;
	}

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
