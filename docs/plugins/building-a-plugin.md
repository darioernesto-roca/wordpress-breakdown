# Building a WordPress plugin

Plugins extend behavior independently of the active theme. Begin with one main
bootstrap file and add classes/directories only when the feature needs them.

## Suggested structure

```text
my-plugin/
├── my-plugin.php          # Header, constants, and bootstrap
├── includes/              # Runtime PHP
├── admin/                 # Admin-only behavior/assets
├── public/                # Front-end behavior/assets
├── languages/             # Translation files
├── uninstall.php          # Permanent cleanup, when appropriate
└── readme.txt              # WordPress.org metadata if distributed there
```

The [`site-notes` practice plugin](../../practice/plugins/site-notes/) deliberately
stays small while demonstrating this split.

## Hooks-first design

Register behavior with actions and filters instead of changing core or another
plugin. Expose your own hooks around meaningful boundaries when other code may
need to extend the plugin. Use unique prefixes or namespaces for every global
symbol.

Activation is for one-time setup such as roles or schema. Deactivation stops
temporary behavior and scheduled events but should not delete user data.
Uninstallation may remove data only when the product's policy and user intent
are clear.

## Secure request checklist

For every form, AJAX action, or REST write:

1. Confirm the current user has the required capability.
2. Verify a nonce for browser-originated intent. A nonce is not authentication.
3. Unslash request data, validate its shape, and sanitize according to meaning.
4. Use WordPress APIs or prepared SQL.
5. Escape every dynamic value for its final output context.

REST routes require a `permission_callback`, including intentionally public
routes. Return `WP_Error` with useful status codes rather than terminating PHP.

## Data and performance

- Store small site settings with the Options API, not one option per row of a
  large dataset.
- Be intentional about autoloading; large autoloaded options affect every request.
- Avoid queries in loops. Prime caches or fetch related objects in batches.
- Version schema changes and make migrations safe to rerun.
- Treat external HTTP calls as unreliable: set timeouts, handle `WP_Error`, and
  cache only when staleness is acceptable.
