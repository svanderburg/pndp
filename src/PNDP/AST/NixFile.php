<?php
namespace PNDP\AST;
use Exception;

/**
 * A Nix file object that gets imported into the Nix store. Paths to a file may
 * be absolute (starting with a /) or relative. For relative paths a directory
 * reference must be given so that it can be correctly resolved to a correct
 * absolute path.
 */
class NixFile extends NixValue
{
	public $baseDir;

	/** Creates a new NixFile instance.
	 *
	 * @param string $value An absolute or relative path to a file
	 * @param string $baseDir Path to the base directory where the file is stored so that a relative path can be resolved
	 */
	public function __construct($value, $baseDir = null)
	{
		parent::__construct($value);
		$this->baseDir = $baseDir;
	}

	/**
	 * @see NixObject::toNixExpr()
	 */
	public function toNixExpr($indentLevel, $format)
	{
		/*
		 * If the path does not start with a / and a module is
		 * defined, we consider the file to have a relative path
		 * and we have to add the module's dirname as prefix
		 */
		if(substr($this->value, 0, 1) != '/' && $this->baseDir !== null)
		{
			$resolvePath = $this->baseDir."/".$this->value;
			$actualPath = realpath($resolvePath); /* Compase a resolved path that does not contain any relative path components */
			
			if($actualPath === false)
				throw new Exception("Cannot resolve path: ".$resolvePath);
		}
		else
			$actualPath = $this->value;

		/* Generate Nix file object */
		if(strpos($actualPath, ' ') === false)
			return $actualPath; /* Filenames without spaces can be placed verbatim */
		else
			return '/. + "'.preg_replace('/\"/', '\\"', $actualPath).'"'; /* Filenames with spaces require some extra steps for conversion */
	}
}
?>
