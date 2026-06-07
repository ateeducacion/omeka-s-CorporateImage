# CorporateImage for Omeka S

<a href="https://ateeducacion.github.io/omeka-s-playground/?blueprint=https%3A%2F%2Fraw.githubusercontent.com%2Fateeducacion%2Fomeka-s-CorporateImage%2Frefs%2Fheads%2Fmain%2Fblueprint.json">
  <img src="https://raw.githubusercontent.com/ateeducacion/omeka-s-CorporateImage/refs/heads/main/.github/assets/playground-preview-button.svg" alt="Try CorporateImage in your browser" width="224">
</a><br>
<small><a href="https://ateeducacion.github.io/omeka-s-playground/?blueprint=https%3A%2F%2Fraw.githubusercontent.com%2Fateeducacion%2Fomeka-s-CorporateImage%2Frefs%2Fheads%2Fmain%2Fblueprint.json">Try in your browser</a></small>

CorporateImage (module name: `PersonalizedHeaderFooter`) lets administrators add custom HTML for the public header and footer of Omeka S sites from the module configuration screen.

## What it does

- Adds a configuration form with two fields: **Personalized Header HTML** and **Personalized Footer HTML**.
- Stores those values in Omeka settings with compatibility support for different Omeka versions.
- Provides install/uninstall handling to create and clean module settings.

## How to use

1. Install and activate the module in Omeka S.
2. Go to **Modules → PersonalizedHeaderFooter → Configure**.
3. Add your header and footer HTML.
4. Save changes.

## Repository structure

```text
omeka-s-CorporateImage/
├── Module.php                 # Main module class and settings handling
├── config/module.ini          # Module metadata and Omeka compatibility
├── config/module.config.php   # Module configuration
├── src/Form/ConfigForm.php    # Admin configuration form
├── blueprint.json             # Omeka S Playground setup
├── test/                      # PHPUnit tests
└── README.md
```

## Requirements

- Omeka S 3.x or 4.x
- PHP 7.4+

## License

GPL-3.0-or-later. See `LICENSE`.
