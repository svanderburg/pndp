<?php
namespace PNDP\AST;

/**
 * Captures the abstract syntax of a Nix inherit statement that imports a value
 * into the current lexical scope. Inheriting value `a` is semantically
 * equivalent to the assignment `a = a` in the Nix expression language.
 */
class NixInherit extends NixObject
{
	/** Name of the scope or undefined to inherit from the current lexical scope */
	public string $scope;

	/**
	 * Creates a new NixInherit instance.
	 *
	 * @param $scope Name of the scope or undefined to inherit from the current lexical scope
	 */
	public function __construct(string $scope = "")
	{
		$this->scope = $scope;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr(int $indentLevel, bool $format): string
	{
		$expr = "inherit";

		if($this->scope !== "")
			$expr .= " (".$this->scope.")";

		return $expr;
	}

	/**
	 * Checks whether this object is equal to another NixInherit object.
	 *
	 * @param $inherit Inherit object to compare to
	 * @return true, if and only if, both objects have the same properties
	 */
	public function equals(NixInherit $inherit): bool
	{
		return $this->scope === $inherit->scope;
	}
}
?>
