<?php
namespace Pkgs;

class StringWriteTest
{
	public static function composePackage(object $args)
	{
		return $args->writeTextFile(array(
			"name" => "stringWriteTest",
			"text" => "I'd like to say: \"Hello World!\""
		));
	}
}
?>
