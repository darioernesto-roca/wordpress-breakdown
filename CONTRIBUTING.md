# Contributing notes and examples

## Content guidelines

- Prefer one subject per Markdown file and link it from the root README.
- Record the WordPress/PHP versions used when behavior is version-specific.
- Link to primary sources such as the WordPress Developer Resources, PHP manual,
  OWASP, or a plugin vendor's own documentation.
- Never commit credentials, production database exports, private customer data,
  uploads, premium packages, or generated dependency directories.
- Explain *why* a snippet is safe; do not label a nonce as authorization or mix
  sanitization (input), validation (business rules), and escaping (output).

## Code checklist

1. Prefix global PHP symbols and option names.
2. Guard direct file access.
3. Use hooks and public APIs rather than modifying WordPress core.
4. Check capabilities and nonces for state-changing requests.
5. Prepare SQL with `$wpdb->prepare()` and escape output at the last moment.
6. Load assets with enqueue APIs and declare dependencies.
7. Test with `WP_DEBUG` enabled and inspect the PHP error log.
8. Verify compatibility with the site's editor, WooCommerce, caching layer, and
   supported PHP/WordPress versions.

## Useful checks

```bash
find practice examples -name '*.php' -print0 | xargs -0 -n1 php -l
git diff --check
```

For production projects, add WordPress Coding Standards through Composer and
automated integration tests in an isolated WordPress test environment.
