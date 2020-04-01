<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Curl
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "curl-7.69.1",
			"src" => $args->fetchurl(array(
				"url" => new NixURL("http://curl.haxx.se/download/curl-7.69.1.tar.bz2"),
				"sha256" => "1s2ddjjif1wkp69vx25nzxklhimgqzaazfzliyl6mpvsa2yybx9g"
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
