# Debugging, deployment, and maintenance

## Local debugging

Enable debugging in a non-production environment and log rather than display
errors. Never commit environment-specific configuration.

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Useful tools include WP-CLI, Query Monitor, browser developer tools, Xdebug, PHP
logs, the Site Health screen, and database inspection with read-only queries.
Do not leave debugging plugins or verbose logs exposed in production.

## Diagnose systematically

1. Capture exact reproduction steps, URL, account role, and timestamps.
2. Check logs and HTTP/browser console errors before changing code.
3. Reproduce on staging with the same relevant versions and sanitized data.
4. Isolate plugin/theme conflicts with a backup and maintenance plan.
5. Fix the root cause, add a regression check, then remove temporary logging.

## Deployment checklist

- Back up files and database; test restoration, not only backup creation.
- Review the diff and dependency/security advisories.
- Run syntax, standards, unit/integration, and browser checks.
- Put schema migrations in versioned, idempotent code and plan rollback.
- Preserve `uploads/` and environment configuration across releases.
- Clear caches in the correct order and warm critical pages where appropriate.
- Run smoke tests for login, forms, search, checkout, scheduled tasks, and email.
- Monitor logs and metrics after release.

For WP Rocket, CDNs, and object caches, test both cold and warm responses.
Purging everything can cause a traffic spike, while failing to purge may retain
old markup or assets. WooCommerce carts, accounts, checkout, and authenticated
responses must not be served from a shared page cache.
