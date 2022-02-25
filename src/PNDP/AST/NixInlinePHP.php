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
	 * @param $code Embedded shell code
	 */
	public function __construct(string $code)
	{
		parent::__construct(new NixExpression("pndpInlineProxy"), array("code" => $code));
	}
}
?>
