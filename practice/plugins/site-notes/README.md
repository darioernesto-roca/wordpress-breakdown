# Site Notes practice plugin

This installable example demonstrates a guarded bootstrap, prefixed global
symbols, an admin-only custom post type, hooks, escaped list-table output,
activation/deactivation, and a bounded WP-Cron cleanup job.

The notes are deliberately not public or REST-accessible. They use WordPress's
standard post capabilities; on a real team plugin, define purpose-specific
capabilities if authors should not see maintenance notes. Uninstall preserves
notes because silent deletion of user content is unsafe.

## Exercises

1. Add a custom taxonomy named “Environment”.
2. Add a settings screen that explicitly opts into deleting data on uninstall.
3. Write integration tests for registration and cleanup.
4. Add a WP-CLI command that lists stale notes without deleting them.
