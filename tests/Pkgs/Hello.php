<?php
namespace Pkgs;
use PNDP\AST\NixURL;

class Hello
{
	public static function composePackage($args)
	{
		return $args->stdenv->mkDerivation(array(
			"name" => "hello-2.10",

			"src" => $args->fetchurl(array(
				"url" => new NixURL("mirror://gnu/hello/hello-2.10.tar.gz"),
				"sha256" => "0ssi1wpaf7plaswqqjwigppsg5fyh99vdlb9kzl7c9lng89ndq1i"
			)),

			"doCheck" => true,

			"meta" => array(
				"description" => "A program that produces a familiar, friendly greeting",
				"homepage" => new NixURL("http://www.gnu.org/software/hello/manual"),
				"license" => "GPLv3+"
			)
		));
	}
}
?>
