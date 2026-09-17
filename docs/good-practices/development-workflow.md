# Development workflow and tools

## Recommended loop

1. Create a ticket with behavior, risks, and acceptance checks.
2. Work on a local disposable site with representative—not sensitive—data.
3. Make the smallest coherent change using hooks and public APIs.
4. Review input, authorization, output, queries, caching, and failure paths.
5. Run automated checks and manually test relevant roles and integrations.
6. Deploy to staging, obtain review, back up, release, and monitor.

## Tool notes

| Tool | Good for | Caution |
| --- | --- | --- |
| WP-CLI | Repeatable admin, cron, database, and content operations | Confirm URL/environment before destructive commands; use dry runs/backups |
| Query Monitor | Hooks, queries, HTTP calls, PHP errors, and template diagnosis | Development/staging tool; it adds overhead and exposes detail |
| Xdebug | Breakpoints, stack traces, and profiling | Profiling is expensive; do not expose its listener publicly |
| PHPCS + WPCS | WordPress coding and compatibility review | Configure project rules and distinguish style from correctness |
| PHPUnit | Fast unit/regression checks | Mock-heavy tests do not replace WordPress integration tests |
| Playwright/Cypress | Browser journeys such as editor or checkout flows | Keep test data deterministic and avoid production targets |

## Compatibility discipline

- Document supported WordPress and PHP versions and test the lowest and latest
  supported combinations.
- Use deprecation logs and replace deprecated APIs before they are removed.
- With Elementor/Avada, test editor and front end; builder rendering can differ.
- With WooCommerce, test guest/customer/admin roles, taxes, coupons, webhooks,
  checkout, emails, refunds, and High-Performance Order Storage as applicable.
- With WP Rocket or another cache, test excluded pages, logged-in users, asset
  optimization, mobile variants, and cache invalidation.

## Performance habits

Measure before optimizing. Watch query count and size, remote calls, autoloaded
options, cache hit rates, generated image sizes, JavaScript execution, and Core
Web Vitals. Cache derived values with a defined invalidation strategy; stale
correct-looking data is often harder to diagnose than slow data.
