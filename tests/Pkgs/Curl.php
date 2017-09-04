<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Curl
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "curl-7.55.1",
			"src" => $args->fetchurl(array(
				"url" => new NixURL("http://curl.haxx.se/download/curl-7.55.1.tar.bz2"),
				"sha256" => "1yvcn7jbh99gsqhc040nky0h15a1cfh8yic6k0a1zhdhscpakcg5"
			)),

			"propagatedBuildInputs" => array(
				$args->zlib(),
				$args->openssl()
			),

			"preConfigure" => "sed -e 's|/usr/bin|/no-such-path|g' -i.bak configure",

			"meta" => array(
				"homepage" => new NixURL("http://curl.haxx.se"),
				"description" => "A command line tool for transferring files with URL syntax"
			)
		));
	}
}
?>
