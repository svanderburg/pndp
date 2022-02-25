<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix with block importing variables of the
 * attribute set parameter into the lexical scope of the body
 */
class NixWith extends NixBlock
{
	/** An expression yielding an attribute set */
	public $withExpr;

	/** Body of the let block containing an arbitrary expression in which the members of the attribute set are imported */
	public $body;

	/**
	 * Creates a new NixWith instance.
	 *
	 * @param $withExpr An expression yielding an attribute set
	 * @param $body Body of the let block containing an arbitrary expression in which the members of the attribute set are imported
	 */
	public function __construct($withExpr, $body)
	{
		$this->withExpr = $withExpr;
		$this->body = $body;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		return "with ".NixGenerator::phpToIndentedNix($this->withExpr, $indentLevel, $format).";\n\n".
			NixGenerator::generateIndentation($indentLevel, $format).NixGenerator::phpToIndentedNix($this->body, $indentLevel, $format);
	}
}
?>
