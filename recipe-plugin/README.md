# Recipe Plugin

A WordPress plugin for managing recipes in the Master Chef website.

## Features

- Custom post type for recipes
- Custom taxonomies: Recipe Categories and Tags
- Custom meta boxes for recipe details:
  - Preparation time
  - Cooking time
  - Servings
  - Difficulty level
  - Ingredients
  - Instructions
- Full Dutch language support
- Responsive recipe display
- Recipe printing functionality
- Adjustable serving sizes

## Usage

### Adding a New Recipe

1. From the WordPress admin dashboard, go to "Recipes" > "Add New"
2. Enter the recipe title and content (main description)
3. Fill in the recipe details in the custom meta boxes:
   - Preparation time (in minutes)
   - Cooking time (in minutes)
   - Number of servings
   - Difficulty level (Easy, Medium, Hard)
   - Ingredients (one per line)
   - Instructions (one step per line)
4. Set recipe categories and tags
5. Add a featured image (this will be used as the recipe cover image)
6. Publish the recipe

### Displaying Recipes

Recipes will be displayed using the standard WordPress template system. You can customize the display by creating recipe-specific templates in your theme:

- `single-recipe.php` - For individual recipe pages
- `archive-recipe.php` - For recipe archives
- `taxonomy-recipe_category.php` - For recipe category pages
- `taxonomy-recipe_tag.php` - For recipe tag pages

## Customization

### CSS

The plugin includes basic CSS styling, which can be overridden in your theme. To customize the appearance, either:

1. Add custom CSS to your theme
2. Edit the plugin's CSS file at `assets/css/recipe-plugin.css`

### Templates

To customize the recipe display, you can create a `templates` folder in the plugin directory and add custom templates.

## Translation

The plugin is fully translatable with Dutch translations included. Additional translations can be added to the `languages` folder.

## Requirements

- WordPress 5.7 or higher
- PHP 7.4 or higher

## Developer Information

### Hooks

The plugin provides several filters and actions for developers to extend its functionality:

- `recipe_plugin_meta_fields` - Filter to modify recipe meta fields
- `recipe_plugin_before_recipe_content` - Action to add content before the recipe
- `recipe_plugin_after_recipe_content` - Action to add content after the recipe

### Custom Templates

You can create custom templates in your theme for recipe displays:

```php
// Example of a custom template part for recipes
function my_custom_recipe_template($content) {
    if (is_singular('recipe')) {
        // Code for custom recipe display
    }
    return $content;
}
add_filter('the_content', 'my_custom_recipe_template');
```