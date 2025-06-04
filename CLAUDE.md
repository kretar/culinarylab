# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This repository contains a WordPress website project called "Master Chef" that uses Docker for local development. It is focused on developing a custom WordPress theme. The main language of the website will be Dutch (nl_NL).

## Environment Setup

The project uses Docker Compose to set up a local WordPress development environment:

```bash
# Start the WordPress environment
docker-compose up -d

# Stop the WordPress environment
docker-compose down

# View container logs
docker-compose logs -f

# Restart containers
docker-compose restart
```

The local WordPress site will be available at http://localhost:8080 after starting the containers.

## WordPress Configuration

- WordPress database credentials:
  - Database: wordpress
  - Username: wordpress
  - Password: wordpress
  - Host: db
- WordPress debugging is enabled (WORDPRESS_DEBUG: 1)

## Theme and Plugin Development

The project includes:
- A custom theme called "masterchef" located in `wp-content/themes/masterchef/`
- A custom plugin called "recipe-plugin" located in `wp-content/plugins/recipe-plugin/`

When developing:
1. Files in the theme and plugin directories are mapped to the WordPress container
2. Changes to these files are immediately reflected in the running WordPress instance
3. No rebuild of the container is necessary when modifying files

## WordPress Theme Structure

A WordPress theme typically includes these important files:

- `style.css` - The main stylesheet with theme metadata
- `functions.php` - Theme functionality and hooks
- `index.php` - Main template file
- `header.php` - Header template
- `footer.php` - Footer template
- `single.php` - Single post template
- `page.php` - Page template
- `archive.php` - Archive template
- `screenshot.png` - Theme thumbnail

When creating new theme files, follow the WordPress template hierarchy and styling conventions.

## Language and Translation

The website will be in Dutch (nl_NL). WordPress language files are included in the `wp-content/languages/` directory. When developing:

1. Ensure all user-facing strings are properly internationalized using WordPress i18n functions
2. Use `__()`, `_e()`, `esc_html__()`, `esc_html_e()` functions with the appropriate text domain
3. Remember that Dutch text may be longer than English equivalents, affecting layout

## Database Persistence

The MySQL database is stored in a Docker volume named `db_data` which persists between container runs.