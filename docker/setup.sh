#!/bin/sh
# Provisions the local development WordPress: install, Dutch locale, theme/plugin
# activation, permalinks and sample content. Safe to re-run: every step checks
# whether it has already been done. Local development only, do not use in production.
set -eu

SITE_URL="${WP_SITE_URL:-http://localhost:8080}"
ADMIN_USER="${WP_ADMIN_USER:-admin}"
ADMIN_PASSWORD="${WP_ADMIN_PASSWORD:-admin}"
ADMIN_EMAIL="${WP_ADMIN_EMAIL:-admin@example.com}"

cd /var/www/html

echo "Waiting for WordPress files and database..."
attempt=0
while true; do
    if [ ! -f wp-config.php ]; then
        reason="wp-config.php not created yet"
    else
        # `core is-installed` exits 1 when the database works but WordPress is not installed
        # yet. PHP warnings on stderr (e.g. missing HTTP_HOST under CLI) are noise; only an
        # actual error message means the database connection failed.
        rc=0
        err=$(wp core is-installed 2>&1) || rc=$?
        if [ "$rc" -eq 0 ] || ! echo "$err" | grep -qE 'Error establishing|^Error:|Fatal error'; then
            break
        fi
        reason="database not reachable: $err"
    fi
    attempt=$((attempt + 1))
    # Report the reason every ~30 seconds so a stuck setup is diagnosable from the logs
    if [ $((attempt % 10)) -eq 0 ]; then
        echo "Still waiting ($reason)"
    fi
    sleep 3
done

if ! wp core is-installed >/dev/null 2>&1; then
    echo "Installing WordPress..."
    wp core install \
        --url="$SITE_URL" \
        --title="Culinary Lab" \
        --admin_user="$ADMIN_USER" \
        --admin_password="$ADMIN_PASSWORD" \
        --admin_email="$ADMIN_EMAIL" \
        --skip-email
    # Remove the default "Hello world!" post, "Sample Page" and first comment
    wp post delete 1 2 --force >/dev/null 2>&1 || true
    wp comment delete 1 --force >/dev/null 2>&1 || true
fi

wp option update blogdescription "Ontdek culinaire experimenten met een wetenschappelijke benadering" >/dev/null

echo "Setting Dutch language..."
wp language core install nl_NL --activate >/dev/null 2>&1 || echo "Could not install nl_NL (no network?), continuing in English"

wp theme activate masterchef
wp plugin activate recipe-plugin
wp rewrite structure '/%postname%/' --hard
wp rewrite flush --hard >/dev/null

# Recipe categories
for entry in "BBQ:bbq" "Dessert:dessert" "Salade:salade" "Taart:taart"; do
    name="${entry%%:*}"
    slug="${entry##*:}"
    if ! wp term list recipe_category --field=slug | grep -qx "$slug"; then
        wp term create recipe_category "$name" --slug="$slug" >/dev/null
    fi
done

# Sample recipes: each one is created only if no recipe with that title exists yet,
# so re-running setup adds new samples to an existing site
recipe_exists() {
    wp post list --post_type=recipe --post_status=any --field=post_title | grep -qxF "$1"
}

create_recipe() {
    # title, category slug, prep, cook, servings, difficulty, featured, ingredients, instructions
    recipe_exists "$1" && return 0
    echo "Creating sample recipe: $1"
    id=$(wp post create --porcelain --post_type=recipe --post_status=publish \
        --post_title="$1" \
        --post_content="Voorbeeldrecept om het thema mee te testen." \
        --meta_input="{\"_recipe_prep_time\":\"$3\",\"_recipe_cook_time\":\"$4\",\"_recipe_servings\":\"$5\",\"_recipe_difficulty\":\"$6\",\"_recipe_featured\":\"$7\",\"_recipe_ingredients\":\"$8\",\"_recipe_instructions\":\"$9\"}")
    wp post term set "$id" recipe_category "$2" >/dev/null
}

create_recipe "Prei-taart" taart 20 40 6 easy 1 \
    "300 - gram - bloem\\n150 - gram - boter\\n3 - stuks - prei\\n200 - ml - room" \
    "Maak het deeg en laat rusten.\\nSnijd de prei en bak kort.\\nVul de vorm en bak 40 minuten op 180 graden."
create_recipe "Gegrilde ribs" bbq 30 180 4 medium 1 \
    "1 - kg - spareribs\\n3 - eetlepels - paprikapoeder\\n2 - eetlepels - bruine suiker" \
    "Wrijf de ribs in met de kruiden.\\nGrill indirect gedurende 3 uur.\\nBestrijk met saus en grill nog 10 minuten."
create_recipe "Griekse salade" salade 15 0 2 easy 0 \
    "2 - stuks - tomaat\\n1 - stuks - komkommer\\n100 - gram - feta\\n2 - eetlepels - olijfolie" \
    "Snijd de groenten.\\nVoeg feta en olijfolie toe.\\nDirect serveren."
create_recipe "Chocolade fondant" dessert 15 12 4 hard 0 \
    "200 - gram - pure chocolade\\n100 - gram - boter\\n3 - stuks - eieren" \
    "Smelt chocolade en boter.\\nMeng de eieren erdoor.\\nBak 12 minuten op 200 graden."

# Multi-part recipe (_recipe_sections), to test the sectioned layout and its print output
if ! recipe_exists "Tiramisu"; then
    echo "Creating sample recipe: Tiramisu"
    id=$(wp post create --porcelain --post_type=recipe --post_status=publish \
        --post_title="Tiramisu" \
        --post_content="Voorbeeldrecept met meerdere onderdelen om het thema mee te testen." \
        --meta_input='{"_recipe_difficulty":"easy"}')
    wp post term set "$id" recipe_category dessert >/dev/null
    wp post meta update "$id" _recipe_sections --format=json '[
      {"title":"Lange vingers",
       "ingredients":"2 - - eieren\n60 - g - fijne kristalsuiker\n50 - g - bloem\n10 - g - ma\u00efzena",
       "instructions":"Verwarm de oven voor op 180 \u00b0C.\nSplits de eieren en klop het eiwit met de helft van de suiker tot stijve pieken. Klop de eidooiers met de resterende suiker schuimig.\nMeng de custard met de bloem. Spatel het eiwit door de eidooiers en zeef de custardbloem erboven.\nRoer goed door en schep het beslag in een spuitzak met een glad spuitmondje.\nSpuit lange vingers op een met bakpapier beklede bakplaat.\nBestrooi de lange vingers met suiker en bak ze circa 15 minuten in de oven."},
      {"title":"Tiramisu",
       "ingredients":"3 - - eiwit\n150 - g - suiker\n3 - - eidooiers\n400 - g - mascarpone\n200 - g - lange vingers\n50 - ml - amandellikeur\n175 - ml - koude koffie\n200 - g - pure chocolade\ncacaopoeder",
       "instructions":"Klop de eiwitten met de helft van de suiker tot stijve pieken.\nKlop de eidooiers met de resterende suiker schuimig.\nRoer de mascarpone los en meng de eidooiers erdoor. Spatel het eiwit erdoor.\nMeng de likeur met de koffie.\nBedek de bodem van een ovenschaal met lange vingers en kwast ze in met het koffiemengsel.\nSchep de helft van het mascarponemengsel erover en rasp er chocolade op.\nLeg er nog een laag lange vingers op, rasp er chocolade over en lepel de rest van het mascarponemengsel erover.\nStrijk de bovenkant en laat de tiramisu opstijven in de koelkast.\nBestuif voor het serveren met cacaopoeder."}
    ]' >/dev/null
fi

# Sample blog content, so the homepage articles section and posts page can be tested
if [ "$(wp option get page_for_posts)" = "0" ]; then
    echo "Creating sample blog content..."
    home_id=$(wp post create --porcelain --post_type=page --post_status=publish --post_title="Home")
    blog_id=$(wp post create --porcelain --post_type=page --post_status=publish --post_title="Technieken")
    wp option update show_on_front page >/dev/null
    wp option update page_on_front "$home_id" >/dev/null
    wp option update page_for_posts "$blog_id" >/dev/null
    wp post create --post_type=post --post_status=publish \
        --post_title="Eiwitten stijf kloppen" \
        --post_content="Voorbeeldtechniek: gebruik een schone, vetvrije kom en klop de eiwitten tot er stijve pieken ontstaan." >/dev/null
fi

echo "Done. Site: $SITE_URL  Login: $ADMIN_USER / $ADMIN_PASSWORD"
