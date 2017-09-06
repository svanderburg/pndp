<?php
namespace PNDP\AST;

/**
 * Captures the abstract syntax of a Nix inherit statement that imports a value
 * into the current lexical scope. Inheriting value `a` is semantically
 * equivalent to the assignment `a = a` in the Nix expression language.
 */
class NixInherit extends NixObject
{
	public $scope;

	/**
	 * Creates a new NixInherit instance.
	 *
	 * @param string $scope Name of the scope or undefined to inherit from the current lexical scope
	 */
	public function __construct($scope = "")
	{
		$this->scope = $scope;
	}

	/**
	 * @see NixObject#toNixExpr
	 */
	public function toNixExpr($indentLevel, $format)
	{
		$expr = "inherit";

		if($this->scope !== "")
			$expr .= " (".$this->scope.")";

		return $expr;
	}

	/**
	 * Checks whether this object is equal to another NixInherit object.
	 *
	 * @return bool true, if and only if, both objects have the same properties
	 */
	public function equals(NixInherit $inherit)
	{
		return $this->scope === $inherit->scope;
	}
}
?>
