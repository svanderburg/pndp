<?php
namespace PNDP;
use Exception;
use PNDP\AST\NixBlock;
use PNDP\AST\NixInherit;
use PNDP\AST\NixObject;

class NixGenerator
{
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

	public static function isAssociativeArray(array $array)
	{
		return($array !== array() && array_keys($array) !== range(0, count($array) - 1));
	}

	public static function isValidIdentifier($value)
	{
		return (preg_match('/^[a-zA-Z\_][a-zA-Z0-9\_\'\-]*$/', $value) === 1
			&& $value != "assert" && $value != "else"
			&& $value != "if" && $value != "import" && $value != "in" && $value != "inherit"
			&& $value != "or" && $value != "rec" && $value != "then" && $value != "with");
	}

	public static function sequentialArrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(count($array) == 0)
			return "[]";
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

	public static function objectKeyToAttrName($key)
	{
		if(NixGenerator::isValidIdentifier($key))
			return $key; // The key can be used as an identifier
		else
			return '"'.preg_replace('/"/', '\"', $key).'"'; // The key contains weird characters or keywords and must be used as a string
	}

	public static function objectMembersToAttrsMembers(array $array, $indentLevel, $format)
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

	public static function associativeArrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(count($array) == 0)
			return "{}";
		else
			return "{\n".NixGenerator::objectMembersToAttrsMembers($array, $indentLevel + 1, $format).NixGenerator::generateIndentation($indentLevel, $format)."}";
	}

	public static function arrayToIndentedNix(array $array, $indentLevel, $format)
	{
		if(NixGenerator::isAssociativeArray($array))
			return NixGenerator::associativeArrayToIndentedNix($array, $indentLevel, $format);
		else
			return NixGenerator::sequentialArrayToIndentedNix($array, $indentLevel, $format);
	}

	public static function objectToIndentedNix($obj, $indentLevel, $format)
	{
		if($obj instanceof NixObject)
			return $obj->toNixExpr($indentLevel, $format);
		else
		{
			$array = get_object_vars($obj);
			return NixGenerator::arrayToIndentedNix($array, $indentLevel, $format);
		}
	}

	public static function booleanToIndentedNix($obj)
	{
		if($obj)
			return "true";
		else
			return "false";
	}

	public static function stringToIndentedNix($obj)
	{
		return '"'.preg_replace(array('/\\\/', '/"/'), array('\\\\\\', '\"'), $obj).'"'; // escape " and / '
	}

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

	public static function phpToNix($obj, $format)
	{
		return NixGenerator::phpToIndentedNix($obj, 0, $format);
	}
}
?>