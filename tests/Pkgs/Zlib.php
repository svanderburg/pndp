<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Zlib
{
	public static function composePackage(object $args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "zlib-1.2.11",
			"src" => $args->fetchurl(array(
				"url" => new NixURL("mirror://sourceforge/libpng/zlib/1.2.11/zlib-1.2.11.tar.gz"),
				"sha256" => "18dighcs333gsvajvvgqp8l4cx7h1x7yx9gd5xacnk80spyykrf3"
			)),
			"configureFlags" => "--shared"
		));
	}
}
?>
