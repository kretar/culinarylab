#!/bin/sh
# Builds the installable theme and plugin zips and release notes into an output directory.
#
# Refuses to build when the version numbers disagree, when the version has no CHANGELOG.md
# entry, or (when RELEASE_TAG is set, as in CI) when the version does not match the tag.
# Each zip contains one fixed top-level folder (culinarylab/ and recipe-plugin/), so
# WordPress installs into that folder instead of inventing one from the zip file name.
#
# Usage: scripts/package.sh [output-dir]      (default: dist)
set -eu
cd "$(dirname "$0")/.."

out="${1:-dist}"
fail() { echo "package.sh: $*" >&2; exit 1; }

theme_css=$(sed -n 's/^Version: *//p' masterchef/style.css | head -1 | tr -d '\r')
theme_php=$(sed -n "s/.*define('MASTERCHEF_VERSION', '\([^']*\)').*/\1/p" masterchef/functions.php | head -1)
plugin_header=$(sed -n 's/^ \* Version: *//p' recipe-plugin/recipe-plugin.php | head -1 | tr -d '\r')
plugin_const=$(sed -n "s/.*define('RECIPE_PLUGIN_VERSION', '\([^']*\)').*/\1/p" recipe-plugin/recipe-plugin.php | head -1)

version="$theme_css"
[ -n "$version" ] || fail "no Version found in masterchef/style.css"
for found in "$theme_php" "$plugin_header" "$plugin_const"; do
    [ "$found" = "$version" ] || fail "version mismatch: style.css=$theme_css functions.php=$theme_php plugin header=$plugin_header plugin constant=$plugin_const (run scripts/bump-version.sh)"
done

if [ -n "${RELEASE_TAG:-}" ] && [ "${RELEASE_TAG#v}" != "$version" ]; then
    fail "tag $RELEASE_TAG does not match version $version in the code"
fi

grep -q "^## $version " CHANGELOG.md || fail "CHANGELOG.md has no '## $version - ...' entry"

mkdir -p "$out"
out=$(cd "$out" && pwd)
stage=$(mktemp -d)
trap 'rm -rf "$stage"' EXIT

# Copy without development leftovers and runtime-generated files
cp -R masterchef "$stage/culinarylab"
cp -R recipe-plugin "$stage/recipe-plugin"
rm -rf "$stage/culinarylab/assets/css/extracted" "$stage/culinarylab/assets/js/generated"
find "$stage" \( -name .DS_Store -o -name .git -o -name .gitignore \) -prune -exec rm -rf {} +

theme_zip="$out/culinarylab-$version.zip"
plugin_zip="$out/recipe-plugin-$version.zip"
rm -f "$theme_zip" "$plugin_zip"
(cd "$stage" && zip -qrX "$theme_zip" culinarylab && zip -qrX "$plugin_zip" recipe-plugin)

# Verify what WordPress will see
check_zip() { # zip, top-level folder, main file, header that must be inside it
    zip="$1"; folder="$2"; file="$3"; header="$4"
    [ -z "$(unzip -Z1 "$zip" | grep -v "^$folder/")" ] || fail "$zip has entries outside $folder/"
    unzip -p "$zip" "$folder/$file" | grep -q "$header" || fail "$zip: $folder/$file has no '$header' header"
}
check_zip "$theme_zip" culinarylab style.css "^Theme Name:"
check_zip "$plugin_zip" recipe-plugin recipe-plugin.php "Plugin Name:"

# Release notes: the CHANGELOG section for this version
awk -v v="$version" '
    $0 ~ "^## " v " " { on = 1; next }
    on && /^## / { exit }
    on { print }
' CHANGELOG.md > "$out/release-notes.md"
[ -s "$out/release-notes.md" ] || fail "the CHANGELOG.md entry for $version is empty"

echo "Built version $version in $out:"
ls -l "$theme_zip" "$plugin_zip" "$out/release-notes.md"
