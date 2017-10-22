<?php
namespace PNDP\AST;

/**
 * Creates embedded shell code in a string performing a PHP
 * invocation to execute an inline PHP code fragment.
 */
class NixInlinePHP extends NixFunInvocation
{
	/**
	 * Creates a new NixInlinePHP instance.
	 *
	 * @param string $code
	 */
	public function __construct($code)
	{
		parent::__construct(new NixExpression("pndpInlineProxy"), array("code" => $code));
	}
}
?>
