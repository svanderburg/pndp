<?php
namespace Pkgs;
use PNDP\AST\NixAttrSet;
use PNDP\AST\NixExpression;
use PNDP\AST\NixFunInvocation;
use PNDP\AST\NixInherit;
use PNDP\AST\NixLet;
use PNDP\AST\NixList;
use PNDP\AST\NixMergeAttrs;
use PNDP\AST\NixURL;
use PNDP\AST\NixRecursiveAttrSet;

class ObjToXML
{
	public static function composePackage($args)
	{
		$data = array(
			"number" => 1,
			"string" => "Hello world",
			"URL" => new NixURL("http://github.com"),
			"URL2" => new NixURL("http://nixos.org/nix/manual/#chap-quick-start"),
			"null" => null,
			"listOfStrings" => [ "a", "b", "c", 1, 2, 3 ],
			"recursiveAttrSet" => new NixRecursiveAttrSet(array(
				"number" => 2
			)),
			"keywords" => array(
				"assert" => 0,
				"else" => 1,
				"if" => 2,
				"in" => 3,
				"inherit" => 4,
				"import" => 5,
				"or" => 6,
				"then" => 7,
				"rec" => 8,
				"with" => 9
			),
			"greeting" => new NixInherit(),
			"hello" => new NixInherit("greeting"),
			"world" => new NixInherit("greeting"),
			"emptyArray" => array(),
			"emptyAttrSet" => new NixAttrSet(array()),
			"emptyList" => new NixList(array()),
			"emptyRecursiveAttrSet" => new NixRecursiveAttrSet(array()),
			"listLikeAttrSet" => new NixAttrSet(array("foo", "bar", "baz")),
			"mergedObject" => new NixMergeAttrs(array(
				"a" => "a",
				"b" => "b"
			), array(
				"a" => "a2",
				"c" => "c"
			))
		);

		return new NixLet(array(
			"greeting" => array(
				"hello" => "Hello ",
				"world" => "world!"
			),
			"dataXML" => new NixFunInvocation(new NixExpression("builtins.toXML"), $data)
		), $args->writeTextFile(array(
			"name" => "objToXML",
			"text" => new NixExpression("dataXML")
		)));
	}
}
?>
