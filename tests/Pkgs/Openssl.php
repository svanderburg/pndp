<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Openssl
{
	public static function composePackage(object $args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "openssl-1.1.1f",

			"src" => $args->fetchurl(array(
				"url" => new NixURL("https://www.openssl.org/source/openssl-1.1.1f.tar.gz"),
				"sha256" => "0d9zv9srjqivs8nn099fpbjv1wyhfcb8lzy491dpmfngdvz6nv0q"
			)),

			"buildInputs" => array($args->perl()),
			"propagatedBuildInputs" => [ $args->zlib() ],

			"preConfigure" => "substituteInPlace config --replace /usr/bin/env \"\$(type -p env)\"",
			"configureScript" => "./config",
			"configureFlags" => array("shared", "--libdir=lib"),
			"makeFlags" => "MANDIR=$(out)/share/man"
		));
	}
}
?>
