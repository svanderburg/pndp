<?php
namespace PNDP;
use Exception;

/**
 * Contains utility functions that can be used to build packages with the Nix package manager.
 */
class PNDPBuild
{
	public static function evaluatePackage($filename, $attr, $format)
	{
		require_once($filename);
		$pkgs = new \Pkgs();
		$pkg = $pkgs->$attr();
		return NixGenerator::phpToNix($pkg, $format);
	}

	public static function evaluatePHPFile($filename, $attr, $format)
	{
		$expr = PNDPBuild::evaluatePackage($filename, $attr, $format);
		print($expr);
	}

	public static function nixBuild($expression, $params)
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

	public static function callNixBuild($nixExpression, $params, $pkgsExpression = null)
	{
		if($pkgsExpression === null)
			$pkgsExpression = "import <nixpkgs> {}";

		/* Generate a Nix expression and evaluate it */
		$expression = "let\n".
			"  pkgs = ".$pkgsExpression.";\n".
			"in\n".
			$nixExpression;

		PNDPBuild::nixBuild($expression, $params);
	}

	public static function pndpBuild($filename, $attr, $format, $showTrace, $keepFailed, $outLink, $noOutLink)
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
