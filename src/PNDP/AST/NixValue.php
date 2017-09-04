<?php
namespace PNDP\AST;

abstract class NixValue extends NixObject
{
	public $value;

	public function __construct($value)
	{
		$this->value = $value;
	}
}
?>
