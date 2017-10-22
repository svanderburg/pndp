<?php
namespace Pkgs;
use PNDP\AST\NixInlinePHP;
use PNDP\AST\NixURL;

class CreateFileWithMessageTest
{
	public static function composePackage($args)
	{
		$buildCommand = <<<EOT
mkdir(getenv("out"));
file_put_contents(getenv("out")."/message.txt", "Hello world written through inline PHP!");
EOT;

		return $args->stdenv->mkDerivation(array(
			"name" => "createFileWithMessageTest",
			"buildCommand" => new NixInlinePHP($buildCommand)
		));
	}
}
?>
