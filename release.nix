{ nixpkgs ? <nixpkgs>
, systems ? [ "x86_64-linux" ]
}:

let
  pkgs = import nixpkgs {};

  jobs = {
    package = pkgs.lib.genAttrs systems (system: (import ./default.nix {
      inherit pkgs system;
      noDev = true;
    }).override {
      executable = true;
    });

    dev = pkgs.lib.genAttrs systems (system: (import ./default.nix {
      inherit pkgs system;
    }).override (oldAttrs: {
      buildInputs = oldAttrs.buildInputs ++ [ pkgs.graphviz ];
      executable = true;
      postInstall = ''
        vendor/bin/phpdoc
        mkdir -p $out/nix-support
        echo "doc api $out/doc" >> $out/nix-support/hydra-build-products
      '';
    }));

    tests = {
      pkgs =
        let
          devPackage = jobs.dev."${builtins.currentSystem}";
          pkgsPhpFile = "${devPackage}/tests/Pkgs.php";
          autoloadPhpFile = "${devPackage}/vendor/autoload.php";

          pndpImportPackage = import ./src/PNDP/importPackage.nix {
            inherit nixpkgs;
            system = builtins.currentSystem;
            pndp = builtins.getAttr (builtins.currentSystem) (jobs.package);
          };
        in
        {
          hello = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "hello"; };
          zlib = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "zlib"; };
          perl = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "perl"; };
          openssl = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "openssl"; };
          curl = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "curl"; };
          stringWriteTest = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "stringWriteTest"; };
          appendFilesTest = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "appendFilesTest"; };
          createFileWithMessageTest = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "createFileWithMessageTest"; };
          sayHello = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "sayHello"; };
          addressPerson = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "addressPerson"; };
          addressPersons = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "addressPersons"; };
          addressPersonInformally = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "addressPersonInformally"; };
          sayHello2 = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "sayHello2"; };
          objToXML = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "objToXML"; };
          instanceToXML = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "instanceToXML"; };
          conditionals = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "conditionals"; };
          HelloModel = pndpImportPackage { inherit pkgsPhpFile autoloadPhpFile; attrName = "HelloModel"; };
        };
    };
  };
in
jobs
