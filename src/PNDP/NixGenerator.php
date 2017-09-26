<?php
namespace PNDP;
use Exception;
use PNDP\AST\NixBlock;
use PNDP\AST\NixInherit;
use PNDP\AST\NixObject;

class NixGenerator
{
	/**
	 * Generates indentation to format the resulting output expression more nicely.
	 *
	 * @param int $indentLevel The indentation level of the resulting sub expression
	 * @param bool $format Indicates whether to nicely format to expression (i.e. generating whitespaces) or not
	 * @return string A string with the amount of whitespaces corresponding to the indent level
	 */
	public static function generateIndentation($indentLevel, $format)
	{
		if($format)
		{
			$expr = "";
			for($i = 0; $i < $indentLevel; $i++)
				$expr .= "  ";

			return $expr;
		}
		else
			return "";
	}

	/**
	 * Checks whether an array is associative.
	 *
	 * @param array $array Array to check
	 * @return bool true is the array is associative, false if it is sequential
	 */
	public static function isAssociativeArray(array $array)
	{
		return($array !== array() && array_keys($array) !== range(0, count($array) - 1));
	}

	private static function isValidIdentifier($value)
	{
		return (preg_match('/^[a-zA-Z\_][a-zA-Z0-9\_\'\-]*$/', $value) === 1
			&& $value != "assert" && $value != "else"
			&& $value != "if" && $value != "import" && $value != "in" && $value != "inherit"
			&& $value != "or" && $value != "rec" && $value != "then" && $value != "with");
	}

	/**
	 * Converts an array to a Nix list.
	 *
	 * @param array $array Array to convert
	 * @param int $indentLevel The indentation level of the resulting sub expression
	 * @param bool $format Indicates whether to nicely format to expression (i.e. generating whitespaces) or not
	 * @return A string representing the corresponding Nix list
	 */
	public static function sequentialArrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(count($array) == 0)
			return "[]"; // Not strictly required, but printing an empty list like this is better than putting a newline between the brackets
		else
		{
			$expr = "[\n";

			foreach($array as $value)
			{
				$listMemberExpr = NixGenerator::phpToIndentedNix($value, $indentLevel + 1, $format);

				/* Some objects in a list require ( ) wrapped around them to make them work, because whitespaces are in principle the delimiter symbols in lists */
				if($value instanceof NixBlock) {
					$listMemberExpr = $value->wrapInParenthesis($listMemberExpr);
				}

				$expr .= NixGenerator::generateIndentation($indentLevel + 1, $format).$listMemberExpr."\n";
			}

			$expr .= NixGenerator::generateIndentation($indentLevel, $format)."]";

			return $expr;
		}
	}

	private static function objectKeyToAttrName($key)
	{
		if(NixGenerator::isValidIdentifier($key))
			return $key; // The key can be used as an identifier
		else
			return '"'.preg_replace('/"/', '\"', $key).'"'; // The key contains weird characters or keywords and must be used as a string
	}

	/**
	 * Converts members of an array to members of an attribute set
	 *
	 * @param array $array Array to convert
	 * @param int $indentLevel The indentation level of the resulting sub expression
	 * @param bool $format Indicates whether to nicely format to expression (i.e. generating whitespaces) or not
	 * @return string A string containing the Nix attribute set members
	 */
	public static function arrayMembersToAttrsMembers(array $array, $indentLevel, $format)
	{
		$expr = "";

		/* Convert inherit objects separately, since they have to be generated differently */

		$first = true;
		$haveInherits = false;
		$previousInherit = null;

		foreach($array as $key => $value)
		{
			if($value instanceof NixInherit)
			{
				$haveInherits = true;

				if($previousInherit === null || !$value->equals($previousInherit)) // If the current inherit applies to the same scope as the previous inherit we can merge them into a single inherit statement. If not, we must terminate it, and generate a new one
				{
					if($first)
						$first = false;
					else
						$expr .= ";\n";

					$expr .= NixGenerator::generateIndentation($indentLevel, $format).$value->toNixExpr($indentLevel, $format);
				}

				$expr .= " ".NixGenerator::objectKeyToAttrName($key);
				$previousInherit = $value;
			}
		}

		if($haveInherits)
			$expr .= ";\n"; // If we have inherits we must terminate it with a semicolon

		/* Process "ordinary" object members */

		foreach($array as $key => $value)
		{
			if(!($value instanceof NixInherit))
				$expr .= NixGenerator::generateIndentation($indentLevel, $format).NixGenerator::objectKeyToAttrName($key)." = ".NixGenerator::phpToIndentedNix($value, $indentLevel, $format).";\n";
		}

		return $expr;
	}

	/**
	 * Converts an array to a Nix attribute set.
	 *
	 * @param array $array Array to convert
	 * @param int $indentLevel The indentation level of the resulting sub expression
	 * @param bool $format Indicates whether to nicely format to expression (i.e. generating whitespaces) or not
	 * @return A string representing the corresponding Nix list
	 */
	public static function associativeArrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(count($array) == 0)
			return "{}"; // Not strictly required, but printing an empty attribute set like this is better that putting a newline between the braces
		else
			return "{\n".NixGenerator::arrayMembersToAttrsMembers($array, $indentLevel + 1, $format).NixGenerator::generateIndentation($indentLevel, $format)."}";
	}

	private static function arrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(NixGenerator::isAssociativeArray($array))
			return NixGenerator::associativeArrayToIndentedNix($array, $indentLevel, $format);
		else
			return NixGenerator::sequentialArrayToIndentedNix($array, $indentLevel, $format);
	}

	private static function objectToIndentedNix($obj, $indentLevel, $format)
	{
		if($obj instanceof NixObject)
			return $obj->toNixExpr($indentLevel, $format);
		else
		{
			$array = get_object_vars($obj);
			return NixGenerator::arrayToIndentedNix($array, $indentLevel, $format);
		}
	}

	private static function booleanToIndentedNix($obj)
	{
		if($obj)
			return "true";
		else
			return "false";
	}

	private static function stringToIndentedNix($obj)
	{
		return '"'.preg_replace(array('/\\\/', '/"/'), array('\\\\\\', '\"'), $obj).'"'; // escape " and / '
	}

	/**
	 * Converts a PHP variable of any type to a semantically equivalent or
	 * similar Nix expression language object. It also uses indentation to
	 * format the resulting sub expression more nicely.
	 *
	 * @param mixed $obj A variable of any type
	 * @param int $indentLevel Contains the indentation level
	 * @param bool $format Indicates whether to nicely format the generated expression
	 * @return string A string containing the converted Nix expression language object
	 */
	public static function phpToIndentedNix($obj, $indentLevel, $format)
	{
		$expr = "";

		switch(gettype($obj))
		{
			case "boolean":
				$expr .= NixGenerator::booleanToIndentedNix($obj);
				break;
			case "integer":
				$expr .= $obj;
				break;
			case "double":
				$expr .= $obj;
				break;
			case "string":
				$expr .= NixGenerator::stringToIndentedNix($obj);
				break;
			case "array":
				$expr .= NixGenerator::arrayToIndentedNix($obj, $indentLevel, $format);
				break;
			case "object":
				$expr .= NixGenerator::objectToIndentedNix($obj, $indentLevel, $format);
				break;
			case "resource":
				throw new Exception("Cannot convert a resource to a Nix expression");
				break;
			case "NULL":
				$expr .= "null";
				break;
			case "unknown type":
				throw new Exception("Cannot convert object of unknown type: ".$obj);
			default:
				throw new Exception("Encountered a totally unrecognizable type: ".$obj);
		}

		return $expr;
	}

	/**
	 * Converts a PHP variable of any type to a semantically equivalent or
	 * similar Nix expression language object.
	 *
	 * @param mixed $obj A variable of any type
	 * @param bool $format Indicates whether to nicely format the generated expression
	 * @return string A string containing the converted Nix expression language object
	 */
	public static function phpToNix($obj, $format)
	{
		return NixGenerator::phpToIndentedNix($obj, 0, $format);
	}
}
?>
