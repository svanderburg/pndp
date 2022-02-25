<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of the Nix attribute set merge operator //
 * which merges the fields of two sets together. If there are duplicate key
 * names the latter takes precedence over the former.
 */
class NixMergeAttrs extends NixBlock
{
	/** An object yielding an attribute set */
	public $left;

	/** An object yielding an attribute set */
	public $right;

	/**
	 * Creates a new NixMergeAttrs instance.
	 *
	 * @param $left An object yielding an attribute set
	 * @param $right An object yielding an attribute set
	 */
	public function __construct($left, $right)
	{
		$this->left = $left;
		$this->right = $right;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		/* Generate the sub expression that yields the left attribute set */
		$leftExpr = NixGenerator::phpToIndentedNix($this->left, $indentLevel, $format);

		if($this->left instanceof NixBlock)
			$leftExpr = $this->left->wrapInParenthesis($leftExpr); // Some objects require ( ) around them to make them work

		/* Generate the sub expression that yields the right attribute set */
		$rightExpr = NixGenerator::phpToIndentedNix($this->right, $indentLevel, $format);

		if($this->right instanceof NixBlock)
			$rightExpr = $this->right->wrapInParenthesis($rightExpr); // Some objects require ( ) around them to make them work

		/* Return the generated sub expression */
		return $leftExpr." // ".$rightExpr;
	}
}
?>
