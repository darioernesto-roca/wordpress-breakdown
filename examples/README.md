# WordPress Examples

Runnable, adaptable PHP snippets extracted from
[`../wordpress-breakdown-with-examples.php`](../wordpress-breakdown-with-examples.php).

In the breakdown file all PHP lives inside `/* ... */` comment blocks, so nothing
executes — it's a study document. These files take the *functional* snippets and
turn them into real, copy-pasteable code with proper escaping, sanitization,
nonces, and inline explanations.

## How to use

- **Learning:** read each file top to bottom — the header comment states the
  real-life use case and the rules that matter for that topic.
- **In a project:** copy the function(s) you need into your theme's
  `functions.php` (child theme recommended) or a small plugin, then adapt the
  `rocadev_` prefixes and slugs.
- Every file guards with `if ( ! defined( 'ABSPATH' ) ) { return; }`, so it does
  nothing if opened outside WordPress.
- Functions are prefixed `rocadev_` to avoid name collisions — rename to your own
  project prefix.

## Files and where they come from

| File | Breakdown section | What it shows |
|------|-------------------|---------------|
| `hooks-actions-and-filters.php` | 5 | Actions vs filters; footer marker, asset enqueue, CTA-after-post, excerpt length, and a custom `do_action`/`add_action` extension point |
| `the-loop-and-wp-query.php` | 6 | The main Loop for a template + a safe secondary `WP_Query` with `wp_reset_postdata()` |
| `shortcodes.php` | 7 | `[current_year]`, `[button url label]` with attributes, and an enclosing `[highlight]...[/highlight]` |
| `enqueue-styles-and-scripts.php` | 8 | Correct global enqueue, cache-busting via `filemtime()`, conditional per-page CSS, `wp_localize_script` |
| `register-custom-post-type.php` | 9 | "Case Studies" CPT with archive, REST support, and an activation flush note |
| `register-taxonomy.php` | 10 | "Industry" taxonomy attached to the CPT, with optional default terms |
| `rest-custom-endpoint.php` | 18.7 / 18.8 | Custom REST routes: list + filter by `?industry=`, single item by ID, shared formatter |
| `rest-custom-search.php` | 18.21 / 18.22 | Live-search REST endpoint + debounced, XSS-safe frontend JS |
| `ajax-load-more.php` | 15.2 | Classic `admin-ajax` "Load More" with nonce verification + frontend JS |
| `coding-standards.php` | 19 | Before/after of the same feature — bad style vs WordPress Coding Standards (prefixing, snake_case, Yoda conditions, sanitize/validate/escape, nonces, DocBlocks) |
| `classic-theme-setup.php` | 20 | Classic theme `functions.php` setup: theme supports, nav menu locations, a footer widget area, a sanitized Customizer setting with selective refresh, and the header/footer template tags that output them |
| `custom-metabox.php` | 20 | Hand-coded custom-field metabox (the manual version of ACF/Meta Box): render UI, save with nonce + capability check + sanitize, read with escaped output |
| `block-patterns.php` | 20 | Registering a block pattern + pattern category in code, plus the file-based `/patterns/` equivalent |
| `block-theme-theme-json.json` | 20 | Commented, realistic `theme.json` for a block theme: palette, fluid typography, spacing scale, element/block styles, and template parts |

## Dependencies between files

Several REST/Loop examples expect the Case Studies content type to exist. Load
these together when testing the full set:

```
register-custom-post-type.php   ->  defines the 'case_study' CPT
register-taxonomy.php           ->  defines the 'industry' taxonomy
rest-custom-endpoint.php        ->  queries both of the above
the-loop-and-wp-query.php       ->  its WP_Query example queries 'case_study'
```

After registering a CPT or a taxonomy with a custom slug, visit
**Settings → Permalinks → Save** once so the pretty URLs (e.g.
`/case-studies/industry/healthcare/`) don't 404.
