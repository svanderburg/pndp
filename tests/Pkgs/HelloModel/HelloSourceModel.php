<?php
namespace Pkgs\HelloModel;
use PNDP\AST\NixASTNode;
use PNDP\AST\NixURL;

class HelloSourceModel extends NixASTNode
{
	private $args;
	private $src;
	private $sha256;

	public function __construct($args)
	{
		$this->args = $args;
		$this->src = "mirror://gnu/hello/hello-2.10.tar.gz";
		$this->sha256 = "0ssi1wpaf7plaswqqjwigppsg5fyh99vdlb9kzl7c9lng89ndq1i";
	}

	/**
	 * @see NixASTConvertable#toNixAST
	 */
	public function toNixAST()
	{
		return $this->args->fetchurl(array(
			"url" => new NixURL($this->src),
			"sha256" => $this->sha256
		));
	}
}
?>
