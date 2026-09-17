# WordPress application structure

WordPress is a PHP application whose core loads an active theme and zero or more
plugins, while most content and configuration live in a MySQL-compatible
database. A web server routes requests to `index.php`; WordPress bootstraps,
builds the main query, chooses a template, and emits a response.

## Important paths

```text
wordpress/
├── wp-admin/                 # Core administration application
├── wp-includes/              # Core APIs and libraries
├── wp-content/
│   ├── mu-plugins/           # Always-loaded site-owned plugins
│   ├── plugins/              # Regular plugins
│   ├── themes/               # Themes
│   └── uploads/              # User-generated media (not source code)
├── wp-config.php             # Environment/database configuration
└── index.php                 # Front controller
```

Treat `wp-admin/` and `wp-includes/` as vendor code. Core updates replace them,
so custom behavior belongs in a plugin, must-use plugin, child theme, or theme.
Keep environment secrets outside version control and use environment-specific
configuration rather than editing code during a deployment.

## Database mental model

The table prefix is configurable and is not always `wp_`.

| Table family | Stores |
| --- | --- |
| `posts`, `postmeta` | Posts, pages, attachments, navigation items, and custom post types |
| `users`, `usermeta` | Accounts, capabilities, and user preferences |
| `options` | Site-wide settings, transients, and cron schedule data |
| `terms`, `term_taxonomy`, `term_relationships`, `termmeta` | Categories, tags, and custom taxonomies |
| `comments`, `commentmeta` | Comments and comment metadata |

Plugins such as WooCommerce may add custom tables. Use the owning API instead of
assuming its current storage model. Avoid direct SQL when a WordPress query or
metadata API expresses the operation; APIs apply caching, filters, and future
compatibility behavior.

## What belongs where?

- **Theme:** presentation, templates, visual assets, theme-supported features.
- **Plugin:** content types and behavior that should survive a theme switch.
- **Must-use plugin:** host/site policy that must load on every request; it cannot
  be disabled in the normal Plugins screen.
- **Uploads:** media only; prevent PHP execution there at the server layer.
- **Database:** content and settings; schema migrations must be versioned.

## Multisite difference

A multisite network shares one codebase and user table while maintaining many
site-specific table sets. Network activation, roles, uploads, and options behave
differently, so test multisite support explicitly rather than assuming it.
