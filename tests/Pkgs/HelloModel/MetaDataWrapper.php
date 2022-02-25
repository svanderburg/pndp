<?php
namespace Pkgs\HelloModel;
use PNDP\AST\NixASTConvertable;
use PNDP\AST\NixURL;

class MetaDataWrapper implements NixASTConvertable
{
	private array $meta;

	public function __construct(array $meta)
	{
		$this->meta = $meta;
	}

	public function toNixAST()
	{
		return array(
			"description" => $this->meta["description"],
			"homepage" => new NixURL($this->meta["homepage"]),
			"license" => $this->meta["license"]
		);
	}
}
?>
