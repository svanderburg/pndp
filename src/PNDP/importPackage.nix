{nixpkgs, system, pndp}:
{pkgsPhpFile, autoloadPhpFile, attrName, format ? false}:

let
  pkgs = import nixpkgs { inherit system; };

  pndpInlineProxy = import ./inlineProxy.nix {
    inherit (pkgs) stdenv writeTextFile php;
    inherit pndp;
  };
in
import (pkgs.stdenv.mkDerivation {
  name = "importPackage-${attrName}.nix";

  buildCommand = pndpInlineProxy {
    name = "importPackage-${attrName}-buildCommand";
    code = ''
      use PNDP\NixGenerator;
      use PNDP\AST\NixFunInvocation;
      use PNDP\AST\NixImport;
      use PNDP\AST\NixInherit;
      use PNDP\AST\NixLet;
      use PNDP\AST\NixStorePath;

      require_once('${autoloadPhpFile}');
      require_once('${pkgsPhpFile}');
      $attr = '${attrName}';
      $pkgs = new \Pkgs();
      $pkg = $pkgs->$attr();

      $expr = new NixLet(array(
          "pkgs" => new NixFunInvocation(new NixImport(new NixStorePath('${nixpkgs}')), array("system" => '${system}')),
          "pndp" => new NixStorePath('${pndp}'),
          "pndpInlineProxy" => new NixFunInvocation(new NixImport(new NixStorePath("${pndp}/share/php/composer-svanderburg-pndp/src/PNDP/inlineProxy.nix")), array(
              "stdenv" => new NixInherit("pkgs"),
              "writeTextFile" => new NixInherit("pkgs"),
              "php" => new NixInherit("pkgs"),
              "pndp" => new NixInherit()
          ))
      ), $pkg);

      if(file_put_contents(getenv('out'), NixGenerator::phpToNix($expr, ${if format then "true" else "false"})) === false)
          exit(1);
    '';
  };
})
