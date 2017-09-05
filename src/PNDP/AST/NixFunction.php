<?php
namespace PNDP\AST;
use PNDP\NixGenerator;

class NixFunction extends NixBlock
{
	public $argSpec;

	public $body;

	public function __construct($argSpec, $body)
	{
		if(gettype($argSpec) === "array" || gettype($argSpec) === "string" || $argSpec instanceof NixList || $argSpec instanceof NixAttrSet)
		{
			$this->argSpec = $argSpec;
			$this->body = $body;
		}
		else
			throw new Exception("The argument specification must be an array, string, Nix list or Nix attribute set!");
	}

	public function toNixExpr($indentLevel, $format)
	{
		$expr = "";

		if(gettype($this->argSpec) == "string")
			$expr .= $this->argSpec.": "; // Use a positional argument when the argument attribute is a string
		else
		{
			$expr .= "{";

			if($this->argSpec instanceof NixAttrSet || (gettype($this->argSpec) == "array" && NixGenerator::isAssociativeArray($this->argSpec)))
			{
				$first = true;

				// An associative array gets converted into an argument attribute set in which the keys correspond to the parameter names and values to the default parameters
				foreach($this->argSpec as $param => $default)
				{
					if($first)
						$first = false;
					else
						$expr .= ", ";

					$expr .= $param;

					/* If the value is defined, consider it a default value */
					if(!$default instanceof NixNoDefault)
						$expr .= " ? ".NixGenerator::phpToIndentedNix($default, $indentLevel + 1, $format);
				}
			}
			else
			{
				// An array gets converted into an argument attribute set with no default parameters
				for($i = 0; $i < count($this->argSpec); $i++)
				{
					$expr .= $this->argSpec[$i];

					if($i < count($this->argSpec) - 1)
						$expr .= ", ";
				}
			}

			$expr .= "}:\n\n".NixGenerator::generateIndentation($indentLevel, $format);
		}

		/* Generate the function body */
		$expr .= NixGenerator::phpToIndentedNix($this->body, $indentLevel, $format);

		/* Return the generated expression */
		return $expr;
	}
}
?>
