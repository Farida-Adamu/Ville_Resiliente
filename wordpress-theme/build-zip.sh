#!/usr/bin/env bash
# Rebuilds ville-resiliente.zip from the theme folder and checks that WordPress will accept it:
# the zip must hold a single top-level "ville-resiliente/" folder whose style.css has a Theme Name header.
set -euo pipefail

cd "$(dirname "$0")"

if ! head -n 20 ville-resiliente/style.css | grep -q '^Theme Name:'; then
	echo "error: ville-resiliente/style.css is missing its 'Theme Name:' header" >&2
	exit 1
fi

rm -f ville-resiliente.zip
zip -rqX ville-resiliente.zip ville-resiliente -x '*.DS_Store' -x '__MACOSX/*'

top_level=$(unzip -Z1 ville-resiliente.zip | cut -d/ -f1 | sort -u)
if [ "$top_level" != "ville-resiliente" ]; then
	echo "error: zip should contain only the ville-resiliente/ folder, found: $top_level" >&2
	exit 1
fi
if ! unzip -p ville-resiliente.zip ville-resiliente/style.css | head -n 20 | grep -q '^Theme Name:'; then
	echo "error: style.css inside the zip has no theme header" >&2
	exit 1
fi

version=$(grep -m1 '^Version:' ville-resiliente/style.css | awk '{print $2}')
echo "Built wordpress-theme/ville-resiliente.zip (theme version $version)"
