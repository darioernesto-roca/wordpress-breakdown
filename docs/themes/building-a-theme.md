# Building a WordPress theme

A theme owns presentation. Content types, business rules, integrations, and
portable site behavior normally belong in plugins so that changing the theme
does not remove them.

## Choose a theme type

- **Block theme:** HTML templates plus `theme.json`; users edit templates through
  the Site Editor. Prefer this for new projects that embrace block workflows.
- **Classic theme:** PHP template hierarchy, `functions.php`, and optionally
  `theme.json`. It remains useful for existing projects and highly controlled
  PHP rendering.
- **Child theme:** safely overrides a maintained parent theme. Do not copy every
  parent file; override only what is needed and review parent updates.

## Minimum classic theme

```text
my-theme/
├── style.css       # Theme header and styles
├── functions.php   # Setup and hooks
└── index.php       # Final template fallback
```

The practice theme expands this into `header.php`, `footer.php`, and `single.php`.
Install it under `wp-content/themes/`, activate it only on a local site, then use
Query Monitor and the template hierarchy to trace a request.

## Implementation order

1. Define supported browsers, PHP/WordPress versions, editor strategy, and
   accessibility requirements.
2. Create the theme header and configure features on `after_setup_theme`.
3. Register menus and enqueue assets; never hard-code stylesheet `<link>` tags.
4. Build semantic templates and escape dynamic values at output.
5. Add `theme.json` design tokens before accumulating one-off CSS values.
6. Test keyboard navigation, zoom, reduced motion, localization, long content,
   missing images, comments, archives, search, and 404 pages.

## Compatibility notes

- Use a child theme for Avada or another maintained parent; direct edits vanish
  on upgrade.
- Elementor templates may bypass ordinary theme content layout. Keep required
  calls such as `wp_head()`, `wp_footer()`, and `the_content()` intact.
- Use WooCommerce-supported hooks and template overrides. Copying old plugin
  templates creates upgrade debt; review override notices after each update.
- Do not remove cache-busting versions or concatenate assets behind WP Rocket's
  back. Test exclusions only when an optimization demonstrably breaks behavior.

## Output safety

Escape by context: `esc_html()` for text, `esc_attr()` for attributes,
`esc_url()` for URLs, and `wp_kses_post()` for trusted post-like HTML. Translation
functions do not automatically make output safe; use combined functions such as
`esc_html_e()` where appropriate.
