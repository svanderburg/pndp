<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Captures the abstract syntax of a Nix let block containing local variables.
 */
class NixLet extends NixBlock
{
	public $value;

	public $body;

	/**
	 * Creates a new NixLet instance.
	 *
	 * @param array $value An arbitrary object that should be represented as a let block. This object is stored as a reference.
	 * @param mixed $body Body of the let block containing an expression that should get evaluated
	 */
	public function __construct(array $value, $body)
	{
		$this->value = $value;
		$this->body = $body;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		$indentation = NixGenerator::generateIndentation($indentLevel, $format);

		return "let\n".
			NixGenerator::arrayMembersToAttrsMembers($this->value, $indentLevel + 1, $format).
			$indentation."in\n".
			$indentation.NixGenerator::phpToIndentedNix($this->body, $indentLevel, $format);
	}
}
?>
