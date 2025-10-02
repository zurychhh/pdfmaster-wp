# PDFMaster Theme

## Purpose

The PDFMaster Theme provides a minimal, Elementor Pro–ready foundation for the PDFMaster web application. It keeps the rendering layer lightweight while exposing full control to Elementor templates and global styles.

## Features

- WordPress 6.4+ and PHP 8.1+ compatible.
- Elementor Pro location support for header, footer, single, and archive templates.
- Clean typography baseline and basic structural layout.
- Theme assets enqueueing with version awareness.
- Translation-ready via `pdfmaster-theme` text domain.

## File Structure

```
pdfmaster-theme/
├── footer.php          # Footer markup and wp_footer hook
├── functions.php       # Theme setup, Elementor hooks, and asset loading
├── header.php          # Header markup with body open handling
├── index.php           # Default loop wrapper for Elementor-managed pages
├── README.md           # Theme overview and structure (this file)
├── screenshot.png      # 400x300 preview displayed in the WP admin
└── style.css           # Theme stylesheet and header metadata
```

## Elementor Notes

- Register Elementor theme locations via `elementor/theme/register_locations`.
- Theme declares generic `elementor` and `elementor-pro` support to keep compatibility flags explicit.

## Development

- Follow WordPress Coding Standards (PHPCS) for all contributions.
- Limit PHP logic within theme templates—business logic should live in custom plugins.
