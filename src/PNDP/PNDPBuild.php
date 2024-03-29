<?php
namespace PNDP;
use Exception;

/**
 * Contains utility functions that can be used to build packages with the Nix package manager.
 */
class PNDPBuild
{
	public static function evaluatePackage(string $filename, string $attr, bool $format): string
	{
		require_once($filename);
		$pkgs = new \Pkgs();
		$pkg = $pkgs->$attr();
		return NixGenerator::phpToNix($pkg, $format);
	}

	public static function evaluatePHPFile(string $filename, string $attr, bool $format): void
	{
		$expr = PNDPBuild::evaluatePackage($filename, $attr, $format);
		print($expr);
	}

	public static function nixBuild(string $expression, array $params): void
	{
		array_push($params, "-");

		/* Compose a string out of the parameters */
		$paramsStr = "";

		foreach($params as $param)
			$paramsStr .= " ".$param;

		/* Invoke nix-build with the parameters and the expression as input through a pipe */
		$process = proc_open("nix-build".$paramsStr, array(
			0 => array("pipe", "r")
		), $pipes);

		fwrite($pipes[0], $expression);
		fclose($pipes[0]);
		$exitStatus = proc_close($process);

		/* Return error if the process invocation failed */
		if($exitStatus != 0)
			throw new Exception("nix-build exited with status: ".$exitStatus);
	}

	public static function callNixBuild(?string $nixExpression, array $params, string $pkgsExpression = null): void
	{
		if($pkgsExpression === null)
			$pkgsExpression = "import <nixpkgs> {}";

		/*
		 * Hacky way to determine whether nijs is deployed by Nix or Composer.
		 * If deployed by the latter, we need to somehow get it in the Nix store
		 * when invoking inline PHP stuff
		 */

		$modulePathComponents = explode('/', __FILE__);

		if(count($modulePathComponents) >= 4)
		{
			$rootPathComponent = $modulePathComponents[count($modulePathComponents) - 4];

			if(substr($rootPathComponent, 32, 1) == "-") // This looks very much like a Nix store path
				$pndpPath = "builtins.storePath ".realpath(dirname(__FILE__)."/../..");
			else
				$pndpPath = "builtins.getAttr (builtins.currentSystem) ((import ".realpath(dirname(__FILE__)."/../../release.nix")." {}).package)";
		}

		/* Generate a Nix expression and evaluate it */
		$expression = "let\n".
			"  pkgs = ".$pkgsExpression.";\n".
			"  pndp = ".$pndpPath.";\n".
			'  pndpInlineProxy = import "${pndp}/share/php/composer-svanderburg-pndp/src/PNDP/inlineProxy.nix" { inherit (pkgs) stdenv writeTextFile php; inherit pndp; };'."\n".
			"in\n".
			$nixExpression;

		PNDPBuild::nixBuild($expression, $params);
	}

	public static function pndpBuild(string $filename, string $attr, bool $format, bool $showTrace, bool $keepFailed, ?string $outLink, bool $noOutLink): void
	{
		/* Compose parameters to nix-build */
		$params = array();

		if($showTrace)
			array_push($params, "--show-trace");

		if($keepFailed)
			array_push($params, "-K");

		if($outLink !== null)
		{
			array_push($params, "-o");
			array_push($params, $outLink);
		}

		if($noOutLink)
			array_push($params, "--no-out-link");

		/* Evaluate the package */
		$expr = PNDPBuild::evaluatePackage($filename, $attr, $format);

		/* Call nix-build */
		PNDPBuild::callNixBuild($expr, $params);
	}
}
?>
