<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Openssl
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "openssl-1.1.0f",

			"src" => $args->fetchurl(array(
				"url" => new NixURL("https://www.openssl.org/source/openssl-1.1.0f.tar.gz"),
				"sha256" => "0r97n4n552ns571diz54qsgarihrxvbn7kvyv8wjyfs9ybrldxqj"
			)),

			"buildInputs" => array($args->perl()),
			"propagatedBuildInputs" => [ $args->zlib() ],

			"configureScript" => "./config",
			"configureFlags" => array("shared", "--libdir=lib"),
			"makeFlags" => "MANDIR=$(out)/share/man"
		));
	}
}
?>
