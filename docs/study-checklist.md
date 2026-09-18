# WordPress study checklist

Use this checklist to work through the repository in a practical order. Read the
linked guide, inspect the related example, and complete the hands-on task before
marking a topic complete. Use a disposable local WordPress installation rather
than a production site.

## 1. WordPress foundations

- [ ] Explain the roles of WordPress core, themes, plugins, must-use plugins,
  uploads, configuration, and the database.
- [ ] Identify the main WordPress directories and explain why customizations
  must not modify `wp-admin/` or `wp-includes/`.
- [ ] Describe what the main WordPress database table families store, including
  posts, metadata, options, users, terms, and comments.
- [ ] Decide whether a feature belongs in a theme, plugin, must-use plugin, or
  environment configuration.
- [ ] Explain the important differences between single-site and multisite
  installations.

Study: [Application structure](fundamentals/application-structure.md).

## 2. Requests, hooks, and queries

- [ ] Trace a front-end request from `index.php` through bootstrap, the main
  query, template selection, and response rendering.
- [ ] Distinguish actions from filters and choose an appropriate hook, priority,
  and accepted-argument count.
- [ ] Explain why globally visible functions, classes, hooks, options, and
  handles need a unique prefix or namespace.
- [ ] Follow the classic and block-theme template hierarchies and identify the
  fallback template for a request.
- [ ] Use the Loop and create a secondary `WP_Query` without corrupting global
  post data.

Study: [Request lifecycle and hooks](fundamentals/request-lifecycle.md),
[`hooks-actions-and-filters.php`](../examples/hooks-actions-and-filters.php), and
[`the-loop-and-wp-query.php`](../examples/the-loop-and-wp-query.php).

## 3. Theme development

- [ ] Compare classic, block, and child themes and choose the right approach for
  a site's ownership and editing requirements.
- [ ] Register theme supports and navigation menus on the correct hooks.
- [ ] Enqueue styles and scripts with declared dependencies and safe cache
  versioning instead of hard-coding asset tags.
- [ ] Build semantic templates that include `wp_head()`, `wp_body_open()`,
  `the_content()`, and `wp_footer()` where appropriate.
- [ ] Use `theme.json` for design tokens and understand how block-theme templates
  and patterns are organized.
- [ ] Test keyboard navigation, localization, long content, missing content,
  reduced motion, and layouts at 200% and 400% zoom.
- [ ] Complete the exercises in the Learning Classic practice theme.

Study: [Building a theme](themes/building-a-theme.md),
[`learning-classic`](../practice/themes/learning-classic/),
[`classic-theme-setup.php`](../examples/classic-theme-setup.php), and the block
theme examples in [`examples/`](../examples/README.md).

## 4. Plugin development

- [ ] Build a guarded plugin bootstrap and organize runtime, admin, public, and
  uninstall responsibilities.
- [ ] Explain when activation, deactivation, and uninstall hooks run and which
  data each lifecycle stage should change.
- [ ] Register a custom post type and taxonomy with portable content ownership
  and intentional rewrite behavior.
- [ ] Create settings, metadata, shortcodes, and extension hooks with prefixed
  identifiers and documented behavior.
- [ ] Use the Options, Metadata, Settings, and HTTP APIs appropriately, including
  an intentional autoload and cache-invalidation strategy.
- [ ] Complete the Site Notes exercises, including integration tests and a safe
  WP-CLI command design.

Study: [Building a plugin](plugins/building-a-plugin.md),
[`site-notes`](../practice/plugins/site-notes/), and the focused plugin examples
listed in the [`examples` guide](../examples/README.md).

## 5. Secure data and request handling

- [ ] Distinguish sanitization, validation, authorization, nonce verification,
  and output escaping.
- [ ] Select the correct escaping function for HTML text, attributes, URLs, and
  trusted post-like markup.
- [ ] Protect form, AJAX, and REST writes with capability checks and nonces where
  applicable; explain why a nonce is not authorization.
- [ ] Register REST routes with a `permission_callback`, validate parameters,
  and return useful `WP_Error` responses.
- [ ] Use WordPress data APIs or prepared SQL rather than concatenating query
  input.
- [ ] Recognize and mitigate XSS, SQL injection, CSRF, IDOR, unsafe uploads,
  path traversal, SSRF, unsafe deserialization, and privilege escalation.
- [ ] Practice incident response: preserve evidence, rotate secrets, determine
  scope, rebuild from trusted sources, patch, and monitor.

Study: [Security and vulnerability patterns](security/hardening-and-vulnerabilities.md),
[`custom-metabox.php`](../examples/custom-metabox.php),
[`ajax-load-more.php`](../examples/ajax-load-more.php), and the REST examples in
[`examples/`](../examples/README.md).

## 6. Scheduled work and reliability

- [ ] Explain why WP-Cron can run late and why scheduled callbacks must tolerate
  retries and overlapping requests.
- [ ] Register, inspect, run, and unschedule a prefixed cron event.
- [ ] Design bounded batches, expiring locks, progress tracking, timeouts, and
  retry backoff for background work.
- [ ] Describe how a system scheduler can invoke WP-Cron reliably and how to
  verify it before disabling request-based spawning.
- [ ] Complete and test the Site Notes cleanup job.

Study: [WP-Cron and scheduled tasks](operations/wp-cron.md) and the
[`site-notes` practice plugin](../practice/plugins/site-notes/).

## 7. Development workflow and quality

- [ ] Write acceptance checks and assess compatibility and rollback risks before
  changing code.
- [ ] Use WP-CLI, Query Monitor, browser developer tools, logs, and Xdebug for
  systematic diagnosis.
- [ ] Run PHP syntax checks, WordPress Coding Standards, automated tests, and
  relevant browser journeys.
- [ ] Test the lowest and latest supported WordPress and PHP versions and review
  deprecation notices.
- [ ] Measure query count, remote calls, autoloaded options, cache behavior,
  image size, JavaScript execution, and Core Web Vitals before optimizing.
- [ ] Record a focused study finding with
  [`notes/NOTE-TEMPLATE.md`](../notes/NOTE-TEMPLATE.md) and link primary
  documentation.

Study: [Development workflow and tools](good-practices/development-workflow.md),
[`coding-standards.php`](../examples/coding-standards.php), and
[the contribution checklist](../CONTRIBUTING.md).

## 8. Compatibility practice

- [ ] Test Elementor and Avada changes in both the editor and front end; use a
  child theme instead of editing a maintained parent.
- [ ] Use WooCommerce hooks and supported APIs, then test relevant roles,
  checkout flows, emails, webhooks, refunds, and HPOS behavior.
- [ ] Test WP Rocket or another cache with logged-in users, excluded pages,
  mobile variants, optimized assets, and correct cache invalidation.
- [ ] Confirm that carts, accounts, checkout, and authenticated responses cannot
  be served from a shared page cache.

Study: the compatibility sections in [Building a theme](themes/building-a-theme.md),
[Development workflow and tools](good-practices/development-workflow.md), and
[Debugging, deployment, and maintenance](operations/debugging-and-deployment.md).

## 9. Deployment and maintenance

- [ ] Configure debugging to log errors without displaying sensitive detail in
  production.
- [ ] Reproduce failures with exact steps, roles, timestamps, versions, and
  sanitized representative data before applying a fix.
- [ ] Back up files and the database and prove that the backup can be restored.
- [ ] Make schema migrations versioned, idempotent, observable, and reversible.
- [ ] Plan cache purges and warming, then smoke-test login, forms, search,
  checkout, scheduled jobs, and email after deployment.
- [ ] Monitor logs and metrics after release and execute the rollback plan when
  acceptance checks fail.

Study: [Debugging, deployment, and maintenance](operations/debugging-and-deployment.md).

## Capstone review

- [ ] Install both projects under [`practice/`](../practice/README.md) on a local
  WordPress site and explain every hook they register.
- [ ] Complete at least one exercise in each practice project using a small,
  reviewable change.
- [ ] Review the change for capabilities, nonces, validation, sanitization,
  escaping, query cost, caching, accessibility, and cleanup behavior.
- [ ] Test relevant Elementor, Avada, WooCommerce, and WP Rocket interactions, or
  document why each integration is outside the change's scope.
- [ ] Run the repository checks, record manual test evidence, and describe a safe
  deployment and rollback plan.
