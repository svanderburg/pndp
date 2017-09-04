<?php
namespace PNDP\AST;

abstract class NixObject
{
	public abstract function toNixExpr($indentLevel, $format);
}
?>
