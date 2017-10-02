{ nixpkgs ? <nixpkgs>
, system ? builtins.currentSystem
}:

let
  pkgs = import nixpkgs { inherit system; };
in
{
  package = (import ./default.nix {
    inherit pkgs system;
    noDev = true;
  }).override {
    executable = true;
  };

  dev = (import ./default.nix {
    inherit pkgs system;
  }).override (oldAttrs: {
    buildInputs = oldAttrs.buildInputs ++ [ pkgs.graphviz ];
    executable = true;
    postInstall = ''
      vendor/bin/phpdoc
      mkdir -p $out/nix-support
      echo "doc api $out/doc" >> $out/nix-support/hydra-build-products
    '';
  });
}
