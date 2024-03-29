<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix of an expression yielding an attribute
 * set and an expression yielding an attribute name that references a member of
 * the former attribute set.
 */
class NixAttrReference extends NixObject
{
	/** An object representing an expression that yields an attribute set */
	public $attrSetExpr;

	/** An object representing an expression that yields an attribute name */
	public $refExpr;

	/** An optional object representing an expression that gets evaluated if the reference does not exist */
	public $orExpr;

	/**
	 * Creates a new NixAttrReference instance.
	 *
	 * @param $attrSetExpr An object representing an expression that yields an attribute set
	 * @param $refExpr An object representing an expression that yields an attribute name
	 * @param $orExpr An optional object representing an expression that gets evaluated if the reference does not exist
	 */
	public function __construct($attrSetExpr, $refExpr, $orExpr = null)
	{
		$this->attrSetExpr = $attrSetExpr;
		$this->refExpr = $refExpr;
		
		if(func_num_args() > 2)
			$this->orExpr = $orExpr;
		else
			$this->orExpr = new NixNoDefault();
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		/* Generate the sub expression that yields the attribute set */
		$attrSetExprStr = NixGenerator::phpToIndentedNix($this->attrSetExpr, $indentLevel, $format);

		if($this->attrSetExpr instanceof NixBlock) // Some object require ( ) around them to make them work
			$attrSetExprStr = $this->attrSetExpr->wrapInParenthesis($attrSetExprStr);

		/* Generate the sub expression that yields the parameter */
		$refExprStr = NixGenerator::phpToIndentedNix($this->refExpr, $indentLevel, $format);

		if($this->refExpr instanceof NixBlock) // Some objects require ( ) around them to make them work
			$refExprStr = $this->refExpr->wrapInParenthesis($refExprStr);

		/* Generate the or expression that gets evaluated if the reference does not exist */

		if($this->orExpr instanceof NixNoDefault)
			$orExprStr = "";
		else
		{
			$orExprStr = NixGenerator::phpToIndentedNix($this->orExpr, $indentLevel, $format);

			if($this->orExpr instanceof NixBlock) // Some objects require ( ) around them to make them work
				$orExprStr = $this->orExpr->wrapInParenthesis(orExprStr);

			$orExprStr = " or ".$orExprStr;
		}

		/* Return the generated sub expression */
		return $attrSetExprStr.".".$refExprStr.$orExprStr;
	}
}
?>
