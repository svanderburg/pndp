<?php
namespace Pkgs;
use PNDP\AST\NixAttrReference;
use PNDP\AST\NixExpression;
use PNDP\AST\NixLet;

class AddressPersonInformally
{
	public static function composePackage(object $args)
	{
		return new NixLet(array(
			"person" => array(
				"firstName" => "Sander",
				"lastName" => "van der Burg"
			)
		), $args->stdenv->mkDerivation(array(
			"name" => "addressPersonInformally",
			"greeting" => new NixAttrReference(new NixExpression("person"), new NixExpression("howToGreet"), "Hi"),
			"firstName" => new NixAttrReference(new NixExpression("person"), new NixExpression("firstName")),
			"buildCommand" => 'echo $greeting $firstName > $out'
		)));
	}
}
?>
