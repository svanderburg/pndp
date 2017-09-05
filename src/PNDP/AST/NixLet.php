<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

class NixLet extends NixBlock
{
	public $value;

	public $body;

	public function __construct(array $value, $body)
	{
		$this->value = $value;
		$this->body = $body;
	}

	public function toNixExpr($indentLevel, $format)
	{
		$indentation = NixGenerator::generateIndentation($indentLevel, $format);

		return "let\n".
			NixGenerator::objectMembersToAttrsMembers($this->value, $indentLevel + 1, $format).
			$indentation."in\n".
			$indentation.NixGenerator::phpToIndentedNix($this->body, $indentLevel, $format);
	}
}
?>
