<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

/**
 * Defines an AST node that is composed of an arbitrary data
 * structure that can be translated into a Nix expression. This class is
 * supposted to be extended by arbitrary classes so that they can expose their
 * properties as a Nix expression.
 *
 * Alternatively, the constructor can be used as an adapter for any object
 * implementing the NixASTConvertable interface.
 */
class NixASTNode extends NixObject implements NixASTConvertable
{
	protected $object;

	/**
	 * Constructs a new NixAST instance.
	 *
	 * @param NixASTConvertable $object Any object implementing the NixASTConvertable interface (optional)
	 */
	public function __construct(NixASTConvertable $object = null)
	{
		$this->object = $object;
	}

	/**
	 * @see NixASTConvertable::toNixAST()
	 */
	public function toNixAST()
	{
		return $this->object->toNixAST();
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		return NixGenerator::phpToIndentedNix($this->toNixAST(), $indentLevel, $format);
	}
}
?>
