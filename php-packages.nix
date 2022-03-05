{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {};
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "svanderburg-pndp";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "MIT";
  };
}
