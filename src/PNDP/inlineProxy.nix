{stdenv, writeTextFile, php, pndp}:
{name ? null, code}:

writeTextFile {
  name = "inline-proxy${if name == null then "" else "-${name}"}";
  executable = true;
  text = ''
    (
    source ${stdenv}/setup
    (
    cat << "__EOF__"
    <?php
    ${code}
    ?>
    __EOF__
    ) | ${php}/bin/php)
  '';
}
