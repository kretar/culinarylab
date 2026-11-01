# Changelog

All notable changes to the Master Chef WordPress theme project will be documented in this file.

## 1.2.1 - 2026-11-01

### Added
- Login and logout links in the header: "Inloggen" for visitors (returns to the page they were on), "Uitloggen" and, for users who can edit content, "Beheer" for logged-in users
- The recipe plugin repairs its recipe URLs by itself after an install or upgrade

### Changed
- Recipe overview, category and tag pages and recipe search show 12 recipes per page, so the last row is no longer left with a single recipe
- The release zips now contain a fixed folder (`culinarylab/` and `recipe-plugin/`), so WordPress installs them under the same name every time instead of a randomly suffixed folder; both zips are attached to one release
- Release builds verify the version numbers, the tag and the changelog

### Fixed
- Every recipe, and the recipe overview, returned a 404 after installing or re-activating the recipe plugin until permalinks were saved

### Upgrade notes
- **One-time migration:** sites installed from the 1.2.0 zips or earlier have the theme and plugin in randomly suffixed folders (for example `culinarylab-Xh8p69PnPlsyLB6m` and `recipe-plugin-1.2.0-3-Llzf5nTCloQ0cuGp`). Upload both new zips, then deactivate the old plugin and activate the new `recipe-plugin`, activate the new `culinarylab` theme and check Appearance > Menus and Widgets (theme settings are stored per theme folder), and finally delete the old folders. Never keep two copies of the plugin active at the same time
- After this, upgrades are: upload the zip and choose "Replace current with uploaded"
- If recipe pages show a 404, save Settings > Permalinks once

## 1.2.0 - 2026-10-31

### Added
- Print layout for recipes: compact header with cover image, one row of recipe details, an empty tick box per ingredient and plain numbered steps; ingredient lists and headings stay together across page breaks
- Editors can pick an icon for each recipe category (Recepten > Categorieën); it is shown on the homepage category cards, with the first letter as fallback
- Inline SVG icons for author, date, category and comments in the post meta
- Local development: the Docker environment now installs and fills WordPress automatically (dev only)

### Changed
- Homepage: the articles section is now "Technieken" ("Alle technieken") and the recipe link reads "Bekijk alle recepten"; the section is hidden when there is no posts page or no posts
- Footer shows the site name instead of "Master Chef"
- Inline styles and scripts are no longer emitted by WordPress instead of being stripped with a regex; `script-src` is limited to `'self'`
- HSTS header includes `includeSubDomains; preload`

### Fixed
- "Alle Artikelen" linked to a recipe when no posts page was set
- Print: missing title and cover image, tick boxes overlapping the ingredients, recipe navigation printed, print dialog opening twice
- "Skip to content" link was always visible; it now appears on keyboard focus
- Search results heading did not show the search term; the custom search also emptied widgets such as Recent Posts
- Debug notice about `wp-block-library-extracted` and block styles being blocked by the CSP
- Missing post meta icons (Font Awesome was never loaded)

### Upgrade notes
- Update the theme and the recipe plugin together: the category icons are stored and chosen in the plugin and shown by the theme
- After upgrading, pick an icon per category under Recepten > Categorieën
- To show "Technieken" on the homepage: create a page, set it as the posts page (Instellingen > Lezen) and publish posts
- Known issue: search does not look inside multi-part recipes yet

## 1.1.4 - 2025-06-07

### Added
- Content Security Policy (CSP) implementation for improved security
- Additional security headers: HSTS, Referrer-Policy, X-Content-Type-Options, X-Frame-Options
- Support for nonces in AJAX requests
- External resource management for better CSP compliance

### Changed
- Converted inline styles to external CSS classes
- Extracted inline data URIs to external files
- Improved admin interface styling for the recipe plugin

## 1.1.3 - 2025-06-07

### Added
- Initial Content Security Policy (CSP) implementation in report-only mode

## 1.1.2 - 2025-06-06

### Added
- Support for HTML links in recipe instructions to link to demonstration videos 

### Fixed
- Issue with storing HTML links in recipe instructions
- Localization for navigation links on recipe pages
- Improved link styling in recipe instructions (removed icon for cleaner appearance)

## 1.1.1 - 2025-06-06

### Changed
- Updated GitHub Actions workflows to run only on version tag pushes

## 1.1.0 - 2025-06-06

### Added
- Ingredient checkbox functionality for shopping list feature
- .gitignore file to exclude macOS system files

### Changed
- Updated favicon and touch icons with new design

### Fixed
- Scientific comment function not found error by moving the function to functions.php
- Container padding in responsive media query
- Hide comments section when printing recipes
- Instruction item padding in recipe plugin for consistent alignment

## 1.0.0 - 2025-05-10

### Added
- Initial release of CulinaryLab theme
- Recipe plugin functionality
- Scientific laboratory-inspired design
- Recipe source functionality