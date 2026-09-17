# Practice projects

These projects are intentionally small but runnable. Copy a project directory—not
the entire repository—to a disposable local WordPress installation:

- `themes/learning-classic/` → `wp-content/themes/learning-classic/`
- `plugins/site-notes/` → `wp-content/plugins/site-notes/`

Activate each in wp-admin or with WP-CLI. They use no third-party dependencies.
The plugin stores private learning notes as a custom post type; the theme renders
ordinary public content. Do not experiment against a production database.
