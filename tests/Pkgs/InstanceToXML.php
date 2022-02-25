<?php
namespace Pkgs;
use PNDP\AST\NixExpression;
use PNDP\AST\NixFunInvocation;
use PNDP\AST\NixLet;
use Pkgs\InstanceToXML\DataClass;

class InstanceToXML
{
	public static function composePackage(object $args)
	{
		$dataObj = new DataClass();

		return new NixLet(array(
			"dataXML" => new NixFunInvocation(new NixExpression("builtins.toXML"), $dataObj)
		), $args->writeTextFile(array(
			"name" => "instanceToXML",
			"text" => new NixExpression("dataXML")
		)));
	}
}
?>
