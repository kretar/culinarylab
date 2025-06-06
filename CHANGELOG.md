# Changelog

All notable changes to the Master Chef WordPress theme project will be documented in this file.

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