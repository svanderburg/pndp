<?php
namespace Pkgs;
use PNDP\AST\NixASTNode;
use Pkgs\HelloModel\HelloSourceModel;
use Pkgs\HelloModel\MetaDataWrapper;

class HelloModel extends NixASTNode
{
	private object $args;
	private string $name;
	private HelloSourceModel $source;
	private array $meta;

	public function __construct(object $args)
	{
		$this->args = $args;

		$this->name = "hello-2.10";
		$this->source = new HelloSourceModel($args);
		$this->meta = array(
			"description" => "A program that produces a familiar, friendly greeting",
			"homepage" => "http://www.gnu.org/software/hello/manual",
			"license" => "GPLv3+"
		);
	}

	/**
	 * @see NixASTConvertable::toNixAST()
	 */
	public function toNixAST()
	{
		$metadataWrapper = new MetaDataWrapper($this->meta);

		return $this->args->stdenv->mkDerivation(array(
			"name" => $this->name,
			"src" => $this->source,
			"doCheck" => true,
			"meta" => new NixASTNode($metadataWrapper)
		));
	}

	public static function composePackage(object $args)
	{
		return new HelloModel($args);
	}
}
?>
