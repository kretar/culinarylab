# CulinaryLab WordPress Theme

![CulinaryLab Theme](masterchef/assets/images/favicon.svg)

A scientific laboratory approach to culinary arts inspired by molecular gastronomy. This WordPress theme and plugin package provides a unique, laboratory-style presentation for recipes with features specifically designed for precision cooking techniques.

## 🧪 Features

- **Scientific Lab Aesthetic**: Clean, precise design with lab-inspired elements
- **Recipe Components System**: Support for multi-part recipes (like cakes with separate base, filling, and topping)
- **Interactive Shopping List**: Checkable ingredient lists for easier shopping
- **Adjustable Servings**: Dynamic recalculation of ingredient quantities
- **Embedded Video Links**: Support for adding links to demonstration videos within recipe steps
- **Taxonomies**: Recipe categorization by type and tags
- **Scientific Protocol Presentation**: Visually structured as experimental protocols
- **Mobile-Responsive Design**: Works on all device sizes
- **Multilingual Support**: Built-in Dutch (nl_NL) and English language support

## 🔬 Getting Started

### Prerequisites

- WordPress 5.8+
- PHP 7.4+
- MySQL 5.7+

### Installation

#### Local Development with Docker

1. Clone the repository:
   ```bash
   git clone https://github.com/kretar/culinarylab.git
   cd culinarylab
   ```

2. Start the Docker environment:
   ```bash
   docker-compose up -d
   ```

3. Wait for the `setup` container to finish (`docker-compose logs -f setup`). It installs WordPress in Dutch, activates the theme and plugin, and adds sample recipes, categories and a blog post.

4. Open http://localhost:8080 (admin at http://localhost:8080/wp-admin, login `admin` / `admin`, for local development only). To start over with a clean site, run `docker-compose down -v`.

#### Production Installation

1. Download the latest release .zip files for both the theme and plugin
2. In your WordPress admin:
   - Navigate to Appearance → Themes → Add New → Upload Theme
   - Upload and activate the `culinarylab-x.x.x.zip` file
   - Navigate to Plugins → Add New → Upload Plugin
   - Upload and activate the `recipe-plugin-x.x.x.zip` file

#### Installing Upgrades

1. Download the latest release .zip files from the [Releases page](https://github.com/kretar/culinarylab/releases)
2. Before upgrading, make a complete backup of your site (files and database)
3. For the theme:
   - In WordPress admin, navigate to Appearance → Themes → Add New → Upload Theme
   - Upload the new `culinarylab-x.x.x.zip` file (WordPress will update the existing theme)
   - The theme update will be applied automatically

4. For the plugin (safest method that preserves your recipe data):
   - Use FTP or your hosting file manager to access your WordPress installation
   - Navigate to `/wp-content/plugins/`
   - Rename the existing `recipe-plugin` folder to `recipe-plugin-old` (this preserves your data while preventing conflicts)
   - In WordPress admin, go to Plugins → Add New → Upload Plugin
   - Upload the new `recipe-plugin-x.x.x.zip` file
   - Activate the newly uploaded plugin
   - Verify all recipes are intact with their data
   - If everything works correctly, you can delete the `recipe-plugin-old` folder

5. Alternative plugin update method (for advanced users):
   - Make sure you have a complete database backup
   - Deactivate the Recipe Plugin in WordPress admin
   - Install a plugin like "WP Reset" that can manage plugin deactivation without data loss
   - Delete the plugin files through WordPress
   - Upload and activate the new version
   
6. Verify that all recipes display correctly after the upgrade

## 🧪 Using the Theme

### Creating Recipes

1. Create a new recipe post through the "Recipes" menu item
2. Add mandatory fields:
   - Title - The recipe name
   - Featured Image - Main recipe photo
   - Description - Introduction to the recipe
   - Preparation Time, Cooking Time, Servings, and Difficulty

3. Add ingredients in the format:
   ```
   Amount - Unit - Ingredient
   ```
   Example:
   ```
   200 - grams - All-Purpose Flour
   2 - tablespoons - Olive Oil
   ```

4. Add instructions (one step per line)
   - You can include HTML links to videos: `Mix thoroughly. <a href="https://example.com/video" target="_blank">Watch demonstration</a>`

5. For multi-component recipes, use the "Recipe Sections" meta box to create separate parts with their own ingredients and instructions

### Customization

The theme is designed to be used with minimal customization, but you can:

1. Add your logo via Appearance → Customize → Site Identity
2. Customize menus via Appearance → Menus
3. Add widgets to the sidebar and footer areas
4. Adjust colors by modifying the CSS variables in `style.css`

## 🧬 Development

### Theme Structure

```
masterchef/
├── assets/
│   ├── css/           # Stylesheets
│   ├── images/        # Theme images and icons
│   └── js/            # JavaScript files
├── inc/
│   ├── recipe-functions.php    # Recipe-specific functions
│   └── template-tags.php       # Helper template functions
├── template-parts/            # Reusable template parts
│   └── recipe/               # Recipe-specific templates
├── functions.php              # Main theme functions
├── style.css                  # Main stylesheet with theme info
└── various template files     # WordPress template files
```

### Plugin Structure

```
recipe-plugin/
├── assets/
│   ├── css/           # Plugin CSS
│   └── js/            # Plugin JavaScript
├── includes/
│   ├── meta-boxes.php  # Custom meta boxes for recipes
│   ├── post-types.php  # Recipe custom post type
│   └── taxonomies.php  # Recipe categories and tags
├── languages/         # Translation files
└── recipe-plugin.php  # Main plugin file
```

### Development Workflow

1. Make changes to the theme or plugin files
2. Test locally using the Docker environment
3. Commit changes following the conventional commits format
4. Create a new tag for releases (will trigger automatic packaging)

## 📦 Release Process

The project uses GitHub Actions for automated builds and releases:

1. Update version numbers in:
   - `masterchef/style.css`
   - `masterchef/functions.php`
   - `recipe-plugin/recipe-plugin.php`

2. Update `CHANGELOG.md` with new version details

3. Commit changes and create a new tag:
   ```bash
   git add .
   git commit -m "Bump version to x.x.x"
   git tag -a vx.x.x -m "Release version x.x.x"
   git push origin main vx.x.x
   ```

4. GitHub Actions will automatically build and create releases with proper .zip packages

## 🧑‍🔬 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/amazing-feature`)
3. Commit your Changes (`git commit -m 'Add some amazing feature'`)
4. Push to the Branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the GPL v2 or later - see the LICENSE file for details.

## 🙏 Acknowledgments

- Inspired by molecular gastronomy and scientific cooking techniques
- Developed for food science enthusiasts and precision home cooks
- Special thanks to all contributors who have helped shape this project