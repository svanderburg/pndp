<?php
namespace Pkgs;
use PNDP\AST\NixFile;
use PNDP\AST\NixURL;

class Fetchurl
{
	public static function composePackage(object $args, array $funArgs)
	{
		/* Determine the component's name */

		if(gettype($funArgs['url']) == "string")
			$urlString = $funArgs['url'];
		else if($funArgs['url'] instanceof NixURL)
			$urlString = $funArgs['url']->value;
		else
			throw new Exception("The specified url is in an unknown format!");

		if(array_key_exists('name', $funArgs))
			$name = $funArgs['name'];
		else
			$name = basename($urlString);

		/* Check whether the right output hash is specified */

		if(array_key_exists('md5', $funArgs) && $funArgs['md5'] != "")
		{
			$outputHashAlgo = "md5";
			$outputHash = $funArgs['md5'];
		}
		else if(array_key_exists('sha1', $funArgs) && $funArgs['sha1'] != "")
		{
			$outputHashAlgo = "sha1";
			$outputHash = $funArgs['sha1'];
		}
		else if(array_key_exists('sha256', $funArgs) || $funArgs['sha256'] != "")
		{
			$outputHashAlgo = "sha256";
			$outputHash = $funArgs['sha256'];
		}
		else
			throw new Exception("No output hash specified! Specify either 'md5', 'sha1', or 'sha256'!");

		/* Pick the right list of mirrors, in case a mirror:// url has been specified */

		if(substr($urlString, 0, strlen("mirror://")) == "mirror://")
		{
			/* Open mirrors config file */
			$mirrorsConfigFile = __DIR__."/fetchurl/mirrors.json";
			$mirrorsConfig = json_decode(file_get_contents($mirrorsConfigFile), true);

			/* Determine the mirror identifier */
			$urlPath = substr($urlString, strlen("mirror://") - 1);
			preg_match('/^\/[a-zA-Z0-9]+\//', $urlPath, $components);
			$mirrorComponent = $components[0];
			$mirror = substr($mirrorComponent, 1, strlen($mirrorComponent) - 2);

			/* Determine the relative path to the file */
			$filePath = substr($urlPath, strlen($mirrorComponent));

			/* Append the file to each mirror */
			$mirrors = $mirrorsConfig[$mirror];

			for($i = 0; $i < count($mirrors); $i++)
				$mirrors[$i] = $mirrors[$i].$filePath;
		}
		else
			$mirrors = array($funArgs['url']);

		/* Create the derivation that specifies the build action */

		return $args->stdenv->mkDerivation(array(
			"name" => $name,
			"mirrors" => $mirrors,

			"builder" => new NixFile("./fetchurl/builder.sh", __DIR__),

			"outputHashAlgo" => $outputHashAlgo,
			"outputHash" => $outputHash,

			"PATH" => getenv('PATH'),

			/*
			 * We borrow these environment variables from the caller to allow easy proxy
			 * configuration. This is impure, but a fixed-output derivation like fetchurl
			 * is allowed to do so since its result is by definition pure.
			 */
			"impureEnvVars" => array("http_proxy", "https_proxy", "ftp_proxy", "all_proxy", "no_proxy"),

			/*
			 * Doing the download on a remote machine just duplicates network
			 * traffic, so don't do that */
			"preferLocalBuild" => true,

			/* We use the host system's curl, which does not work in a chroot */
			"__noChroot" => true
		));
	}
}
?>
