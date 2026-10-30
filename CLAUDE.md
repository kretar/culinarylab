# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

"CulinaryLab" (internally "Master Chef"): a WordPress theme + companion plugin presenting recipes with a scientific-laboratory aesthetic. The site language is Dutch (nl_NL). There is no build step, package manager, linter, or test suite — it is plain PHP/CSS/JS served by WordPress.

Two deliverables live at the repo root (not under `wp-content/`):
- `masterchef/` — the theme (text domain `masterchef`)
- `recipe-plugin/` — the plugin (text domain `recipe-plugin`) that owns the data model

## Commands

```bash
docker-compose up -d      # WordPress at http://localhost:8080 (MySQL 8, db_data volume persists)
docker-compose logs -f setup   # provisioning progress
docker-compose down -v    # also wipe database and WordPress files for a fresh install
docker-compose down
docker-compose logs -f
```

`docker-compose.yml` bind-mounts `./masterchef` and `./recipe-plugin` into the container's `wp-content/themes|plugins/`, so edits are live without rebuilds. `WORDPRESS_DEBUG` is on. A one-shot `setup` service (WP-CLI, `docker/setup.sh`) provisions the site on every `up`: install (admin/admin), nl_NL, theme + plugin activation, permalinks, sample recipes/categories/blog page. It is idempotent; extend the script rather than configuring through the admin UI. DB credentials are `wordpress`/`wordpress` on host `db`.

Releases: bump the version in `masterchef/style.css`, `masterchef/functions.php` (`MASTERCHEF_VERSION`) and `recipe-plugin/recipe-plugin.php` (header + `RECIPE_PLUGIN_VERSION`), update `CHANGELOG.md`, then push a `vX.Y.Z` tag. The `.github/workflows/package-theme.yml` / `package-plugin.yml` workflows zip each directory and create a GitHub release.

## Architecture

**Theme/plugin split.** The plugin registers the `recipe` post type, the `recipe_category` and `recipe_tag` taxonomies, and all meta boxes (`recipe-plugin/includes/`). The theme only renders: `single-recipe.php`, `archive-recipe.php`, `taxonomy-recipe_*.php`, `front-page.php`, `search.php`, with helpers in `masterchef/inc/recipe-functions.php` and `inc/template-tags.php`. Theme templates will show nothing useful if the plugin is inactive.

**Recipe data** is stored as post meta with a `_recipe_` prefix: `_recipe_prep_time`, `_recipe_cook_time`, `_recipe_servings`, `_recipe_difficulty`, `_recipe_source`, `_recipe_source_url`, `_recipe_ingredients`, `_recipe_instructions`, `_recipe_sections`. Ingredients are free text, one per line, in `Amount - Unit - Ingredient` format and parsed in the theme. `_recipe_sections` holds multi-component recipes (e.g. cake base/filling/topping), each with its own ingredients and instructions. Changing the format means updating both `recipe-plugin/includes/meta-boxes.php` (save/sanitize) and the theme parsers/`assets/js/recipe.js` (servings scaling, shopping-list checkboxes).

**Strict no-inline / CSP policy (the non-obvious part of `masterchef/functions.php`).** The theme sends an enforcing `Content-Security-Policy` with `default-src 'none'` and an HSTS header, so *inline `<style>`, inline `<script>`, and external hosts not allow-listed will be blocked*. To make WordPress comply, `functions.php` aggressively:
- strips inline styles/scripts, emoji scripts, speculationrules, global/classic/block styles (output-buffer regex in `masterchef_clean_head_output`, `script_loader_tag`/`style_loader_tag` filters),
- moves inline CSS attached to handles into `assets/css/extracted/*.css`,
- writes the AJAX nonce/`ajaxurl` to `assets/js/generated/nonce.js` on every page load (`masterchef_vars` global) instead of using `wp_localize_script`. This directory is written at runtime and is untracked.

Consequences when developing: never add inline `<style>`/`<script>`/`style=""`-dependent JS or `onclick=` handlers; put code in files under `assets/` and enqueue it in `masterchef_scripts()`. Any new third-party origin (fonts, CDN, embeds) must be added to the CSP in `masterchef_add_csp_headers()`. Page-specific CSS (`front-page.css`, `search.css`, `recipe.css`) is enqueued conditionally there.

**Search.** `masterchef_recipe_search_query()` (`pre_get_posts`) replaces WordPress's default `s` search on the main query with a custom `$wpdb`-based search (it clears `s`); `search.php` + `search.css` render results. Comments are restricted to logged-in members (`culinarylab_restrict_comments_to_members`).

## Language and i18n

All user-facing strings must go through `__()`, `_e()`, `esc_html__()`, `esc_html_e()` with the correct text domain (`masterchef` or `recipe-plugin`). The only shipped translation file is `recipe-plugin/languages/recipe-plugin-nl_NL.po`; the theme calls `load_theme_textdomain` on `MASTERCHEF_DIR/languages`, but that directory does not exist yet. Dutch strings run longer than English, so check layouts.
