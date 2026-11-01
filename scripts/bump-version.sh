#!/bin/sh
# Sets the theme and plugin version in every place that holds it.
# Usage: scripts/bump-version.sh 1.2.1
set -eu
cd "$(dirname "$0")/.."

version="${1:-}"
case "$version" in
    [0-9]*.[0-9]*.[0-9]*) ;;
    *) echo "Usage: $0 X.Y.Z (for example 1.2.1)" >&2; exit 1 ;;
esac

perl -pi -e "s/^Version: .*/Version: $version/" masterchef/style.css
perl -pi -e "s/define\('MASTERCHEF_VERSION', '[^']*'\)/define('MASTERCHEF_VERSION', '$version')/" masterchef/functions.php
perl -pi -e "s/^ \* Version: .*/ * Version: $version/; s/define\('RECIPE_PLUGIN_VERSION', '[^']*'\)/define('RECIPE_PLUGIN_VERSION', '$version')/" recipe-plugin/recipe-plugin.php

echo "Version set to $version in:"
grep -n "^Version:" masterchef/style.css
grep -n "MASTERCHEF_VERSION'" masterchef/functions.php
grep -nE "^ \* Version:|RECIPE_PLUGIN_VERSION'" recipe-plugin/recipe-plugin.php

if ! grep -q "^## $version " CHANGELOG.md; then
    echo
    echo "Next: add a '## $version - YYYY-MM-DD' section to CHANGELOG.md (the release build requires it)."
fi
