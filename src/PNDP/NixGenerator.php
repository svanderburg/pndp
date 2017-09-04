<?php
namespace PNDP;
use Exception;
use PNDP\AST\NixObject;
use PNDP\AST\NixBlock;

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

	public static function arrayToIndentedNix(array $array, $indentLevel, $format)
	{
		$indentation = NixGenerator::generateIndentation($indentLevel, $format);

		if(count($array) === 0)
			$expr = "[]";
		else if(NixGenerator::isAssociativeArray($array))
		{
			$expr = "{\n";

			foreach($array as $key => $value)
			{
				if(NixGenerator::isValidIdentifier($key))
					$attrName = $key; // The key can be used as an identifier
				else
					$attrName = '"'.preg_replace('/"/', '\"', $key).'"'; // The key contains weird characters or keywords and must be used as a string

				$expr .= NixGenerator::generateIndentation($indentLevel + 1, $format).$attrName." = ".NixGenerator::phpToIndentedNix($value, $indentLevel + 1, $format).";\n";
			}

			$expr .= $indentation."}";
		}
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

			$expr .= $indentation."]";
		}

		return $expr;
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
